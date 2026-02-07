<?php

namespace App\Http\Controllers\Backend\Orders;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderGroup;
use App\Models\OrderItem;
use App\Models\OrderUpdate;
use App\Models\User;
use App\Notifications\DeliverymanAssignNotification;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class OrdersController extends Controller
{
    # construct
    public function __construct()
    {
        $this->middleware(['permission:orders'])->only('index');
        $this->middleware(['permission:manage_orders'])->only(['show', 'updatePaymentStatus', 'updateDeliveryStatus']);
    }

    # get all orders
    public function index(Request $request)
    {
        $searchKey = null;
        $searchCode = null;
        $deliveryStatus = null;
        $paymentStatus = null;
        $locationId = null;
        $posOrder = 0;

        $orders = Order::latest();

        # conditional 
        if ($request->search != null) {
            $searchKey = $request->search;
            $orders = $orders->where(function ($q) use ($searchKey) {
                $customers = User::where('name', 'like', '%' . $searchKey . '%')
                    ->orWhere('phone', 'like', '%' . $searchKey)
                    ->pluck('id');
                $q->orWhereIn('user_id', $customers);
            });
        }

        if ($request->code != null) {
            $searchCode = $request->code;
            $orders = $orders->where(function ($q) use ($searchCode) {
                $orderGroup = OrderGroup::where('order_code', $searchCode)->pluck('id');
                $q->orWhereIn('order_group_id', $orderGroup);
            });
        }

        if ($request->delivery_status != null) {
            $deliveryStatus = $request->delivery_status;
            $orders = $orders->where('delivery_status', $deliveryStatus);
        }

        if ($request->payment_status != null) {
            $paymentStatus = $request->payment_status;
            $orders = $orders->where('payment_status', $paymentStatus);
        }

        if ($request->location_id != null) {
            $locationId = $request->location_id;
            $orders = $orders->where('location_id', $locationId);
        }


        if ($request->is_pos_order != null) {
            $posOrder = $request->is_pos_order;
        }

        $orders = $orders->where(function ($q) use ($posOrder) {
            $orderGroup = OrderGroup::where('is_pos_order', $posOrder)->pluck('id');
            $q->orWhereIn('order_group_id', $orderGroup);
        });

        $orders = $orders->paginate(paginationNumber());
        $locations = Location::where('is_published', 1)->latest()->get();
        return view('backend.pages.orders.index', compact('orders', 'searchKey', 'locations', 'locationId', 'searchCode', 'deliveryStatus', 'paymentStatus', 'posOrder'));
    }

    # show order details
    public function show($id)
    {
        $order = Order::findOrFail($id);
        $deliverymen = User::where('is_active', 1)->where('user_type', 'deliveryman')->where('location_id', $order->orderGroup->location_id)->latest()->get();
        return view('backend.pages.orders.show', compact('order', 'deliverymen'));
    }

    # assign deliveryman
    public function assignDeliveryman(Request $request)
    {
        $order = Order::findOrFail((int)$request->order_id);
        $order->deliveryman_id = $request->deliveryman_id ?? null;
        $order->save();

        $deliveryman = User::whereId($order->deliveryman_id)->first();

        OrderUpdate::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'note' =>  $deliveryman != null ? $deliveryman->name . ' has been assigned for delivery.' : 'Deliveryman has been removed.',
        ]);

        $deliveryman->notify(new DeliverymanAssignNotification($order));

        return true;
    }
    # update payment status 
    public function updatePaymentStatus(Request $request)
    {
        $order = Order::findOrFail((int)$request->order_id);
        $order->payment_status = $request->status;
        $order->save();

        OrderUpdate::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'note' => 'Payment status updated to ' . ucwords(str_replace('_', ' ', $request->status)) . '.',
        ]);

        // todo::['mail notification']
        return true;
    }

    # update delivery status
    public function updateDeliveryStatus(Request $request)
    {
        $order = Order::findOrFail((int)$request->order_id);

        if ($order->delivery_status != orderCancelledStatus() && $request->status == orderCancelledStatus()) {
            $this->addQtyToStock($order);
        }

        if ($order->delivery_status == orderCancelledStatus() && $request->status != orderCancelledStatus()) {
            $this->removeQtyFromStock($order);
        }

        $order->delivery_status = $request->status;
        $order->save();

        OrderUpdate::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'note' => 'Delivery status updated to ' . ucwords(str_replace('_', ' ', $request->status)) . '.',
        ]);

        // todo::['mail notification'] 
        return true;
    }

    # add qty to stock 
    private function addQtyToStock($order)
    {
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        foreach ($orderItems as $orderItem) {
            $stock = $orderItem->product_variation->product_variation_stock;
            $stock->stock_qty += $orderItem->qty;
            $stock->save();

            $product = $orderItem->product_variation->product;
            $product->total_sale_count += $orderItem->qty;
            $product->save();

            if ($product->categories()->count() > 0) {
                foreach ($product->categories as $category) {
                    $category->total_sale_count += $orderItem->qty;
                    $category->save();
                }
            }
        }
    }

    # remove qty from stock  
    private function removeQtyFromStock($order)
    {
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        foreach ($orderItems as $orderItem) {
            $stock = $orderItem->product_variation->product_variation_stock;
            $stock->stock_qty -= $orderItem->qty;
            $stock->save();

            $product = $orderItem->product_variation->product;
            $product->total_sale_count -= $orderItem->qty;
            $product->save();

            if ($product->categories()->count() > 0) {
                foreach ($product->categories as $category) {
                    $category->total_sale_count -= $orderItem->qty;
                    $category->save();
                }
            }
        }
    }

    # download invoice
    public function downloadInvoice($id)
    {

        $data = $this->invoiceData($id);
        return PDF::loadView('backend.pages.orders.invoice', $data, [], [])->download(getSetting('order_code_prefix') . $data['orderCode'] . '.pdf');


    }
    #print 
    public function printInvoice($id)
    {       

        $data = $this->invoiceData($id);
        $pdf = PDF::loadView('backend.pages.orders.invoice', $data, [], []);
        return $pdf->stream(getSetting('order_code_prefix') . $data['orderCode'] . '.pdf');
    }

    #invoice data
    public function invoiceData($order_id):array
    {
        $data = [];
        if (session()->has('locale')) {
            $language_code = session()->get('locale', Config::get('app.locale'));
        } else {
            $language_code = env('DEFAULT_LANGUAGE');
        }

        if (session()->has('currency_code')) {
            $currency_code = session()->get('currency_code', Config::get('app.currency_code'));
        } else {
            $currency_code = env('DEFAULT_CURRENCY');
        }

        if (Language::where('code', $language_code)->first()->is_rtl == 1) {
            $data['direction'] = 'rtl';
            $data['default_text_align'] = 'right';
            $data['reverse_text_align'] = 'left';
        } else {
            $data['direction'] = 'ltr';
            $data['default_text_align'] = 'left';
            $data['reverse_text_align'] = 'right';
        }

        $currency_code = env('INVOICE_LANG');

        $data['font_family'] = env('INVOICE_FONT');

        $selected_language = Language::where('code', $language_code)->first();

        if($selected_language && $selected_language->font){
            $data['font_family'] = $selected_language->font;
        }else{
            $data['font_family'] = "THSarabunNew.ttf";            
        }

        // if ($currency_code == 'BDT' || $currency_code == 'bdt' || $language_code == 'bd' || $language_code == 'bn') {
        //     # bengali font
        //     $data['font_family'] = "'Hind Siliguri','sans-serif'";
        // } elseif ($currency_code == 'KHR' || $language_code == 'kh') {
        //     # khmer font
        //     $data['font_family'] = "'Khmeros','sans-serif'";

        // } elseif ($language_code == 'ar-sa') {

        //     dd($language_code);
        //     # Armenia font
        //     $data['font_family'] = "'Janna LT Bold','sans-serif'";
        //     $data['font_name'] = "'Janna LT Bold','sans-serif'";

        // } elseif ($currency_code == 'AMD') {
        //     # Armenia font
        //     $data['font_family'] = "'Janna LT Bold.ttf','sans-serif'";
        // } elseif ($currency_code == 'AED' || $currency_code == 'EGP' || $language_code == 'sa' || $currency_code == 'IQD' || $language_code == 'ir') {
        //     # middle east/arabic font
        //     $data['font_family'] = "'XBRiyaz','sans-serif'";
        // } else {
        //     # general for all
        //     $data['font_family'] = "'Roboto','sans-serif'";
        // }

        $data['order'] = Order::findOrFail((int)$order_id);
        $data['orderCode'] =  $data['order']->orderGroup->order_code;


        return $data;
    }
}
