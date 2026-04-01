<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Country;
use App\Models\Currency;
use App\Models\OrderItem;
use App\Models\OrderGroup;
use App\Models\CouponUsage;
use App\Models\RewardPoint;
use App\Models\LogisticZone;
use App\Http\Controllers\Backend\Orders\OrdersController;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Illuminate\Http\Request;
use App\Models\LogisticZoneCity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Models\ScheduledDeliveryTimeList;
use Illuminate\Support\Facades\Notification;
use Throwable;

use App\Notifications\OrderPlacedNotification;
use App\Notifications\AdminOrderPlacedNotification;
use App\Http\Controllers\Backend\Payments\PaymentsController;
use Modules\PaymentGateway\Http\Services\PaymentGatewayService;

class CheckoutController extends Controller
{
    # checkout
    public function index()
    {
        $carts = Cart::where('user_id', auth()->user()->id)->where('location_id', session('stock_location_id'))->get();

        if (count($carts) > 0) {
            checkCouponValidityForCheckout($carts);
        }

        $user = auth()->user();
        $addresses = $user->addresses()->latest()->get();

        $countries = Country::isActive()->get();
        $activeGateways = null;
        if(isModuleActive('PaymentGateway')) {
            $activeGateways = \Modules\PaymentGateway\Entities\PaymentGateway::where('is_active', 1)->where('gateway', '!=', 'Cash_on_Delivery')->isActive()->get();
        }
        return view('frontend.default.pages.checkout.checkout', [
            'carts'          => $carts,
            'user'           => $user,
            'addresses'      => $addresses,
            'countries'      => $countries,
            'activeGateways' => $activeGateways
        ]);
    }

    # checkout logistic
    public function getLogistic(Request $request)
    {
        $city_id = $request->city_id;
        $state_id = $request->state_id;

        $query = LogisticZoneCity::query();

        if ($city_id) {
            $query->where('city_id', $city_id);
        } elseif ($state_id) {
            $query->whereIn('city_id', function ($q) use ($state_id) {
                $q->select('id')->from('cities')->where('state_id', $state_id);
            });
        } else {
            // Fallback for empty location
            $query->where('city_id', 0);
        }

        $logisticZoneCities = $query->get()->unique('logistic_id');

        return [
            'logistics' => getRender('inc.logistics', ['logisticZoneCities' => $logisticZoneCities]),
            'summary'   => getRender('pages.partials.checkout.orderSummary', ['carts' => Cart::where('user_id', auth()->user()->id)->where('location_id', session('stock_location_id'))->get()])
        ];
    }

    # checkout shipping amount
    public function getShippingAmount(Request $request)
    {
        $carts              = Cart::where('user_id', auth()->user()->id)->where('location_id', session('stock_location_id'))->get();
        $logisticZone       = LogisticZone::find((int)$request->logistic_zone_id);
        $shippingAmount     = isFreeShippingActive($carts) ? 0 : $logisticZone->standard_delivery_charge;
        return getRender('pages.partials.checkout.orderSummary', ['carts' => $carts, 'shippingAmount' => $shippingAmount]);
    }

    # complete checkout process
    public function complete(Request $request)
    {

        $user    = auth()->user();
        $userId  = $user->id;
        $carts   = Cart::where('user_id', $userId)->where('location_id', session('stock_location_id'))->get();
        $cartIds = [];
        try {

            DB::beginTransaction();
            if (count($carts) > 0) {
                if ($request->boolean('same_as_shipping') || !$request->filled('billing_address_id')) {
                    $request->merge([
                        'billing_address_id' => $request->shipping_address_id,
                    ]);
                }

                $shippingAddress = $user->addresses()->find($request->shipping_address_id);
                if (!$shippingAddress || (!$shippingAddress->city_id && !$shippingAddress->state_id)) {
                    flash(localize('Please update your shipping address and select a location for delivery'))->error();
                    return back();
                }

                if (!$request->filled('chosen_logistic_zone_id') && $shippingAddress) {
                    $query = LogisticZoneCity::query();
                    if ($shippingAddress->city_id) {
                        $query->where('city_id', $shippingAddress->city_id);
                    } else {
                        $query->whereIn('city_id', function ($q) use ($shippingAddress) {
                            $q->select('id')->from('cities')->where('state_id', $shippingAddress->state_id);
                        });
                    }

                    $availableLogistics = $query->get()->unique('logistic_id');

                    if ($availableLogistics->count() === 1) {
                        $request->merge([
                            'chosen_logistic_zone_id' => $availableLogistics->first()->logistic_zone_id,
                        ]);
                    }
                }

                if (!$request->filled('payment_method')) {
                    $paymentMethods = [];

                    if (getSetting('enable_cod') == 1) {
                        $paymentMethods[] = 'cod';
                    }

                    if (getSetting('enable_wallet_checkout') == 1) {
                        $paymentMethods[] = 'wallet';
                    }

                    if (isModuleActive('PaymentGateway')) {
                        $gatewayMethods = \Modules\PaymentGateway\Entities\PaymentGateway::where('is_active', 1)
                            ->where('gateway', '!=', 'Cash_on_Delivery')
                            ->isActive()
                            ->pluck('gateway')
                            ->toArray();

                        $paymentMethods = array_merge($paymentMethods, $gatewayMethods);
                    }

                    if (count($paymentMethods) === 1) {
                        $request->merge([
                            'payment_method' => $paymentMethods[0],
                        ]);
                    }
                }

                # check if coupon applied -> validate coupon
                $couponResponse = checkCouponValidityForCheckout($carts);
                if ($couponResponse['status'] == false) {
                    flash($couponResponse['message'])->error();
                    return back();
                }

                # check carts available stock -- todo::[update version] -> run this check while storing OrderItems
                foreach ($carts as $cart) {

                    $product = $cart->product_variation->product;

                    if ($product->max_purchase_qty >= $cart->qty && $cart->qty >= $product->min_purchase_qty) {
                        $productVariationStock = $cart->product_variation->product_variation_stock ? $cart->product_variation->product_variation_stock->stock_qty : 0;
                        if ($cart->qty > $productVariationStock) {
                            $message = $cart->product_variation->product->collectLocalization('name') . ' ' . localize('is out of stock');
                            flash($message)->error();
                            return back();
                        }
                    } else {
                        $message = localize('Minimum and maximum order quantity is ') . $product->min_purchase_qty . ' & ' . $product->max_purchase_qty . ' ' . localize('for this product: ') . $cart->product_variation->product->collectLocalization('name');

                        flash($message)->error();
                        return back();
                    }
                }

                # create new order group
                $orderGroup                                     = new OrderGroup;
                $orderGroup->user_id                            = $userId;
                $orderGroup->shipping_address_id                = $request->shipping_address_id;
                $orderGroup->billing_address_id                 = $request->billing_address_id;
                $orderGroup->location_id                        = session('stock_location_id');
                $orderGroup->phone_no                           = $request->phone;
                $orderGroup->alternative_phone_no               = $request->alternative_phone;
                $orderGroup->sub_total_amount                   = getSubTotal($carts, false, '', false);
                $orderGroup->total_tax_amount                   = getTotalTax($carts);
                $orderGroup->total_coupon_discount_amount       = 0;
                if (getCoupon() != '') {
                    # todo::[for eCommerce] handle coupon for multi vendor
                    $orderGroup->total_coupon_discount_amount   = getCouponDiscount(getSubTotal($carts, false), getCoupon());
                    # [done->codes below] increase coupon usage counter after successful order
                }
                $logisticZone = LogisticZone::where('id', $request->chosen_logistic_zone_id)->first();
                if (!$logisticZone) {
                    flash(localize('No delivery option is available for the selected address'))->error();
                    return back();
                }
                # todo::[for eCommerce] handle exceptions for standard & express
                $orderGroup->total_shipping_cost                = isFreeShippingActive($carts) ? 0 : $logisticZone->standard_delivery_charge;

                // to convert input price to base price
                if (Session::has('currency_code')) {
                    $currency_code = Session::get('currency_code', Config::get('app.currency_code'));
                } else {
                    $currency_code = env('DEFAULT_CURRENCY');
                }
                $currentCurrency = Currency::where('code', $currency_code)->first();

                $orderGroup->total_tips_amount                  = $request->tips / $currentCurrency->rate; // convert to base price;

                $isTaxInclusive = getSetting('taxes_inclusive') == '1';
                $orderGroup->grand_total_amount = $orderGroup->sub_total_amount + ($isTaxInclusive ? 0 : $orderGroup->total_tax_amount) + $orderGroup->total_shipping_cost + $orderGroup->total_tips_amount - $orderGroup->total_coupon_discount_amount;


                if ($request->payment_method == "wallet") {
                    $balance = (float) $user->user_balance;

                    if ($balance < $orderGroup->grand_total_amount) {
                        flash(localize("Your wallet balance is low"))->error();
                        return back();
                    }
                }
                $orderGroup->save();

                # order -> todo::[update version] make array for each vendor, create order in loop
                $order = new Order;
                $order->order_group_id  = $orderGroup->id;
                $order->shop_id         = $carts[0]->product_variation->product->shop_id;
                $order->user_id         = $userId;
                $order->location_id     = session('stock_location_id');
                if (getCoupon() != '') {
                    $order->applied_coupon_code         = getCoupon();
                    $order->coupon_discount_amount      = $orderGroup->total_coupon_discount_amount; // todo::[update version] calculate for each vendors
                }
                $order->total_admin_earnings            = $orderGroup->grand_total_amount;
                $order->logistic_id                     = $logisticZone->logistic_id;
                $order->logistic_name                   = optional($logisticZone->logistic)->name;
                $order->shipping_delivery_type          = $request->shipping_delivery_type;

                if ($request->shipping_delivery_type == getScheduledDeliveryType()) {
                    $timeSlot = ScheduledDeliveryTimeList::where('id', $request->timeslot)->first(['id', 'timeline']);
                    $timeSlot->scheduled_date = $request->scheduled_date;
                    $order->scheduled_delivery_info = json_encode($timeSlot);
                }

                $order->shipping_cost                   = $orderGroup->total_shipping_cost; // todo::[update version] calculate for each vendors
                $order->tips_amount                     = $orderGroup->total_tips_amount; // todo::[update version] calculate for each vendors

                $order->save();

                # order items
                $total_points = 0;
                foreach ($carts as $cart) {
                    $orderItem                       = new OrderItem;
                    $orderItem->order_id             = $order->id;
                    $orderItem->product_variation_id = $cart->product_variation_id;
                    $orderItem->qty                  = $cart->qty;
                    $orderItem->location_id          = session('stock_location_id');
                    $orderItem->unit_price           = variationDiscountedPrice($cart->product_variation->product, $cart->product_variation);
                    $orderItem->total_tax            = variationTaxAmount($cart->product_variation->product, $cart->product_variation);
                    $orderItem->total_price          = $orderItem->unit_price * $orderItem->qty;
                    $orderItem->save();

                    $product = $cart->product_variation->product;
                    $product->total_sale_count += $orderItem->qty;

                    # reward points
                    if (getSetting('enable_reward_points') == 1) {
                        $orderItem->reward_points = $product->reward_points * $orderItem->qty;
                        $total_points += $orderItem->reward_points;
                    }

                    // minus stock qty
                    try {
                        $productVariationStock = $cart->product_variation->product_variation_stock;
                        $productVariationStock->stock_qty -= $orderItem->qty;
                        $productVariationStock->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        flash(localize($th->getMessage()))->error();
                        return back();
                    }
                    $product->stock_qty -= $orderItem->qty;
                    $product->save();



                    # category sales count
                    if ($product->categories()->count() > 0) {
                        foreach ($product->categories as $category) {
                            $category->total_sale_count += $orderItem->qty;
                            $category->save();
                        }
                    }

                    $cartIds[] = $cart->id;
                   
                }

                # reward points
                if (getSetting('enable_reward_points') == 1) {
                    $reward = new RewardPoint;
                    $reward->user_id = $userId;
                    $reward->order_group_id = $orderGroup->id;
                    $reward->total_points = $total_points;
                    $reward->status = "pending";
                    $reward->save(); 
                }

                $order->reward_points = $total_points;
                $order->save();

                # increase coupon usage
                if (getCoupon() != '' && $orderGroup->total_coupon_discount_amount > 0) {
                    if ($request->payment_method == "cod" || $request->payment_method == "wallet") {
                        $coupon = Coupon::where('code', getCoupon())->first();
                        $coupon->total_usage_count += 1;
                        $coupon->save();

                        # coupon usage by user
                        $couponUsageByUser = CouponUsage::where('user_id', auth()->user()->id)->where('coupon_code', $coupon->code)->first();
                        if (!is_null($couponUsageByUser)) {
                            $couponUsageByUser->usage_count += 1;
                        } else {
                            $couponUsageByUser = new CouponUsage;
                            $couponUsageByUser->usage_count = 1;
                            $couponUsageByUser->coupon_code = getCoupon();
                            $couponUsageByUser->user_id = $userId;
                        }
                        $couponUsageByUser->save();
                        removeCoupon();
                    }
                }

                # payment gateway integration & redirection

                $orderGroup->payment_method = $request->payment_method;
                if ($request->payment_method != "cod" && $request->payment_method != "wallet" && $request->payment_method != "") {
                    $orderGroup->order->update(['delivery_status' => orderPendingStatus()]);
                }
                $orderGroup->save();

                if ($request->payment_method != "cod" && $request->payment_method != "wallet" && $request->payment_method != "") {
                    $request->session()->put('payment_type', 'order_payment');
                    $request->session()->put('order_code', $orderGroup->order_code);
                    $request->session()->put('payment_method', $request->payment_method);

                    DB::commit();
                    # init payment
                    $payment = new PaymentsController;
                    return $payment->initPayment();
                } else if ($request->payment_method == "wallet") {
                    $orderGroup->payment_status = paidPaymentStatus();
                    $orderGroup->order->update(['payment_status' => paidPaymentStatus()]); # for multi-vendor loop through each orders & update
                    $orderGroup->save();

                    $user->user_balance -= $orderGroup->grand_total_amount;
                    $user->save();
                }
                if(!empty($cartIds)) {
                    $carts   = Cart::where('user_id', $userId)->whereIn('id', $cartIds)->delete();
                }
                DB::commit();
                $this->sendOrderPlacedNotification($orderGroup, $user);
                flash(localize('Your order has been placed successfully'))->success();
                return redirect()->route('checkout.success', $orderGroup->order_code);
                
            }

            DB::commit();
        } catch (\Throwable $th) {
           Log::info('checkout issue :'. $th->getMessage());
            DB::rollBack();
            flash($th->getMessage())->error();
            return back();
        }



        flash(localize('Your cart is empty'))->error();
        return back();
    }

    # order successful
    public function success($code)
    {
        $orderGroup = OrderGroup::where('user_id', auth()->user()->id)->where('order_code', $code)->first();
        return view('frontend.default.pages.checkout.invoice', ['orderGroup' => $orderGroup]);
    }


    # order invoice
    public function invoice($code)
    {
        $orderGroup = OrderGroup::where('user_id', auth()->user()->id)->where('order_code', $code)->first();
        $user = auth()->user();
        return view('frontend.default.pages.checkout.invoice', ['orderGroup' => $orderGroup]);
    }

    # download invoice
    public function downloadInvoice($code)
    {
        $orderGroup = OrderGroup::where('user_id', auth()->user()->id)->where('order_code', $code)->first();
        if (!$orderGroup) {
            abort(404);
        }
        $order = $orderGroup->order;
        if (!$order) {
            abort(404);
        }

        $data = (new OrdersController)->invoiceData($order->id);
        $pdf = Pdf::loadView('backend.pages.orders.invoice', $data, [], []);
        return $pdf->download(getSetting('order_code_prefix') . $data['orderCode'] . '.pdf');
    }

    # update payment status
    public function updatePayments($payment_details)
    {
        $orderGroup = OrderGroup::where('order_code', session('order_code'))->first();
        $payment_method = session('payment_method');

        $orderGroup->payment_status = paidPaymentStatus();
        $orderGroup->order->update([
            'payment_status' => paidPaymentStatus(),
            'delivery_status' => orderPlacedStatus()
        ]); # for multi-vendor loop through each orders & update

        $orderGroup->payment_method = $payment_method;
        $orderGroup->payment_details = $payment_details;
        $orderGroup->save();

        # increment coupon usage if not already done (for online payments)
        if ($orderGroup->order->applied_coupon_code) {
            $coupon = Coupon::where('code', $orderGroup->order->applied_coupon_code)->first();
            if ($coupon) {
                $coupon->total_usage_count += 1;
                $coupon->save();

                # coupon usage by user
                $couponUsageByUser = CouponUsage::where('user_id', $orderGroup->user_id)->where('coupon_code', $coupon->code)->first();
                if (!is_null($couponUsageByUser)) {
                    $couponUsageByUser->usage_count += 1;
                } else {
                    $couponUsageByUser = new CouponUsage;
                    $couponUsageByUser->usage_count = 1;
                    $couponUsageByUser->coupon_code = $coupon->code;
                    $couponUsageByUser->user_id = $orderGroup->user_id;
                }
                $couponUsageByUser->save();
            }
        }

        $this->sendOrderPlacedNotification($orderGroup, auth()->user());

        // cart empty
        Cart::where('user_id', auth()->user()->id)->delete();

        clearOrderSession();
        flash(localize('Your order has been placed successfully'))->success();
        return redirect()->route('checkout.success', $orderGroup->order_code);
    }

    private function sendOrderPlacedNotification(OrderGroup $orderGroup, $user)
    {
        if (!$user || empty($user->email) || !$orderGroup->order) {
            Log::warning('Order email skipped: missing user email or order.', [
                'order_code' => $orderGroup->order_code,
                'user_id' => optional($user)->id,
                'email' => optional($user)->email,
            ]);
            return;
        }

        try {
            // Notify Customer
            Notification::send($user, new OrderPlacedNotification($orderGroup->order));

            // Notify Admin(s)
            $adminEmails = getSetting('admin_order_notification_email');
            if ($adminEmails) {
                $emails = array_map('trim', explode(',', $adminEmails));
                foreach ($emails as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        try {
                            Notification::route('mail', $email)->notify(new AdminOrderPlacedNotification($orderGroup->order));
                        } catch (\Exception $e) {
                            Log::error('Admin order notification email failed for: ' . $email, [
                                'order_code' => $orderGroup->order_code,
                                'message' => $e->getMessage(),
                            ]);
                        }
                    }
                }
            }
        } catch (Throwable $e) {
            Log::error('Order confirmation email failed.', [
                'order_code' => $orderGroup->order_code,
                'user_id' => $user->id,
                'email' => $user->email,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
