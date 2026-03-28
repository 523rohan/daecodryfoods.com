<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ localize('Order Confirmation') }}</title>
    <style type="text/css">
        /* RESET STYLES */
        body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important; background-color: #F8FAFC; font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        table { border-spacing: 0; border-collapse: collapse; width: 100%; }
        td { padding: 0; vertical-align: top; }
        img { border: 0; line-height: 100%; outline: none; text-decoration: none; display: block; }
        p, h1, h2, h3 { margin: 0; }
        
        /* LAYOUT */
        .wrapper { width: 100%; table-layout: fixed; background-color: #F8FAFC; padding: 20px 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #FFFFFF; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
        
        /* HEADER */
        .header { padding: 25px 40px 15px; text-align: center; border-bottom: 1px solid #F1F5F9; }
        .logo { margin: 0 auto 15px; max-height: 40px; width: auto; }
        .header-title { font-size: 20px; font-weight: 800; color: #1E293B; letter-spacing: -0.02em; }
        .order-meta { font-size: 13px; color: #64748B; margin-top: 6px; }
        
        /* CONTENT */
        .content { padding: 20px 40px; }
        
        /* INFO GRID */
        .section-title { font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; }
        .info-card { background-color: #F8FAFC; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .info-text { font-size: 13px; color: #334155; line-height: 1.5; }
        .info-name { font-weight: 700; color: #1E293B; margin-bottom: 2px; display: block; }
        
        /* ITEMS TABLE */
        .items-header { border-bottom: 2px solid #F1F5F9; padding-bottom: 8px; margin-bottom: 10px; }
        .item-row { border-bottom: 1px solid #F1F5F9; padding: 10px 0; }
        .item-name { font-size: 14px; font-weight: 600; color: #1E293B; margin-bottom: 2px; }
        .item-variation { font-size: 12px; color: #64748B; }
        .item-price { font-size: 13px; color: #475569; font-weight: 500; }
        
        /* TOTALS */
        .totals-container { margin-top: 20px; padding-top: 15px; border-top: 2px solid #F1F5F9; }
        .total-item { margin-bottom: 6px; font-size: 13px; color: #64748B; }
        .total-value { font-weight: 600; color: #334155; float: right; }
        .grand-total { margin-top: 12px; padding-top: 12px; border-top: 1px solid #F1F5F9; font-size: 16px; font-weight: 800; color: #1E293B; }
        .grand-total-value { float: right; color: #10B981; } /* Premium Green for total */
        
        /* FOOTER */
        .footer { padding: 25px 40px; text-align: center; background-color: #F1F5F9; }
        .footer-text { font-size: 12px; color: #64748B; line-height: 1.5; }
        
        /* RESPONSIVE */
        @media screen and (max-width: 600px) {
            .wrapper { padding: 20px 10px; }
            .content { padding: 25px; }
            .header { padding: 30px 25px 20px; }
            .info-col { width: 100% !important; display: block; margin-bottom: 20px; }
        }
        
        .address-text { font-size: 13px; color: #475569; line-height: 1.5; }
        .product-table-head { background-color: #F8FAFC; border-bottom: 2px solid #E2E8F0; }
        .product-table-head th { padding: 12px 8px; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; text-align: left; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- HEADER -->
            <div class="header">
                @php
                    $logo = getSetting('admin_panel_logo');
                @endphp
                @if($logo)
                    <img src="{{ uploadedAsset($logo) }}" alt="{{ getSetting('system_title') }}" class="logo" />
                @else
                    <h1 style="color: #10B981; margin-bottom: 10px;">{{ getSetting('system_title') }}</h1>
                @endif
                <h2 class="header-title">{{ localize('Order Confirmed!') }}</h2>
                <p class="order-meta">{{ localize('Order ID') }}: #{{ getSetting('order_code_prefix') }}{{ $order->orderGroup->order_code }} &bull; {{ date('M d, Y', strtotime($order->created_at)) }}</p>
            </div>

            <!-- CONTENT -->
            <div class="content">
                <!-- SHIPPING & BILLING -->
                <table width="100%">
                    <tr>
                        <td class="info-col" width="50%" style="padding-right: 15px;">
                            <div class="section-title">{{ localize('Shipping To') }}</div>
                            <div class="info-card">
                                <span class="info-name">{{ optional($order->user)->name }}</span>
                                <div class="info-text">
                                    @if ($order->orderGroup->is_pos_order)
                                        {{ $order->orderGroup->pos_order_address }}
                                    @else
                                        @php 
                                            $shippingAddress = $order->orderGroup->shippingAddress; 
                                            $shipCity = $shippingAddress->city_id ? optional($shippingAddress->city)->name : $shippingAddress->city;
                                        @endphp
                                        @if($shippingAddress)
                                            <div class="address-text">
                                                {{ $shippingAddress->address }}@if($shippingAddress->landmark), {{ $shippingAddress->landmark }}@endif<br>
                                                {{ $shipCity }}@if($shippingAddress->state_id), {{ optional($shippingAddress->state)->name }}@endif @if($shippingAddress->pincode) - {{ $shippingAddress->pincode }}@endif<br>
                                                {{ optional($shippingAddress->country)->name }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="info-col" width="50%" style="padding-left: 15px;">
                            <div class="section-title">{{ localize('Payment Info') }}</div>
                            <div class="info-card">
                                <span class="info-name">{{ localize('Method') }}</span>
                                <div class="info-text">
                                    {{ Str::title(Str::replace('_', ' ', $order->orderGroup->payment_method)) }}<br>
                                    <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; background-color: #DCFCE7; color: #166534; font-size: 12px; font-weight: 700; margin-top: 4px;">{{ Str::upper($order->payment_status) }}</span>
                                </div>
                                @if($order->logistic_name)
                                    <div class="section-title" style="margin-top: 15px; margin-bottom: 4px;">{{ localize('Logistic') }}</div>
                                    <div class="info-text">{{ $order->logistic_name }}</div>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- ITEMS -->
                <div class="section-title" style="margin-top: 10px;">{{ localize('Order Summary') }}</div>
                <div class="items-header"></div>
                
                @php 
                    $orderItems = $order->orderItems;
                @endphp
                <table width="100%" style="margin-top: 20px;">
                    <thead class="product-table-head">
                        <tr>
                            <th width="50%">{{ localize('Product') }}</th>
                            <th width="15%" align="center">{{ localize('Base') }}</th>
                            <th width="10%" align="center">{{ localize('Tax') }}</th>
                            <th width="10%" align="center">{{ localize('Qty') }}</th>
                            <th width="15%" align="right">{{ localize('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderItems as $item)
                        @php 
                            $product = $item->product_variation->productWithTrashed;
                            $taxPerUnit = variationTaxAmount($product, $item->product_variation);
                            $basePricePerUnit = $item->unit_price - $taxPerUnit;
                        @endphp
                        <tr class="item-row">
                            <td style="padding: 15px 8px;">
                                <div class="item-name">{{ $product->collectLocalization('name') }}</div>
                                @php $variations = generateVariationOptions($item->product_variation->combinations); @endphp
                                @if(count($variations) > 0)
                                    <div class="item-variation">
                                        @foreach ($variations as $variation)
                                            {{ $variation['name'] }}: @foreach ($variation['values'] as $value) {{ $value['name'] }} @endforeach{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </div>
                                @endif
                                <div style="font-size: 11px; color: #94A3B8; margin-top: 4px;">{{ localize('Unit Price') }}: {{ formatPrice($item->unit_price) }}</div>
                            </td>
                            <td align="center" style="padding: 15px 8px; vertical-align: middle; color: #475569; font-size: 13px;">{{ formatPrice($basePricePerUnit) }}</td>
                            <td align="center" style="padding: 15px 8px; vertical-align: middle; color: #475569; font-size: 13px;">{{ formatPrice($taxPerUnit) }}</td>
                            <td align="center" style="padding: 15px 8px; vertical-align: middle; color: #1E293B; font-weight: 600; font-size: 14px;">{{ $item->qty }}</td>
                            <td align="right" style="padding: 15px 8px; vertical-align: middle; font-weight: 700; color: #1E293B; font-size: 14px;">{{ formatPrice($item->total_price) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- TOTALS -->
                <div class="totals-container">
                    <div class="total-item">{{ localize('Subtotal') }} <span class="total-value">{{ formatPrice($order->orderGroup->sub_total_amount) }}</span></div>
                    @if($order->orderGroup->total_tips_amount > 0)
                        <div class="total-item">{{ localize('Tips') }} <span class="total-value">{{ formatPrice($order->orderGroup->total_tips_amount) }}</span></div>
                    @endif
                    <div class="total-item">{{ localize('Logistic Charge') }} <span class="total-value">{{ formatPrice($order->orderGroup->total_shipping_cost) }}</span></div>
                    @if ($order->orderGroup->total_coupon_discount_amount > 0)
                        <div class="total-item" style="color: #EF4444;">{{ localize('Coupon Discount') }}{{ $order->applied_coupon_code ? ' (' . $order->applied_coupon_code . ')' : '' }} <span class="total-value">-{{ formatPrice($order->orderGroup->total_coupon_discount_amount) }}</span></div>
                    @endif
                    @php
                        $isTaxInclusive = getSetting('taxes_inclusive') == '1';
                    @endphp
                    <div class="total-item">{{ $isTaxInclusive ? '' : '(+) ' }}{{ localize('Tax') }}{{ $isTaxInclusive ? ' (' . localize('Included') . ')' : '' }} <span class="total-value">{{ formatPrice($order->orderGroup->total_tax_amount) }}</span></div>
                    @if ($order->orderGroup->is_pos_order && $order->orderGroup->total_discount_amount > 0)
                        <div class="total-item" style="color: #EF4444;">{{ localize('POS Discount') }} <span class="total-value">-{{ formatPrice($order->orderGroup->total_discount_amount) }}</span></div>
                    @endif
                    
                    <div class="grand-total">
                        {{ localize('Grand Total') }} <span class="grand-total-value">{{ formatPrice($order->orderGroup->grand_total_amount) }}</span>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="footer">
                <p class="footer-text"><strong>{{ localize('Thank you for your order!') }}</strong> &bull; {{ getSetting('invoice_thanksgiving') }}</p>
                <div style="margin-top: 10px; border-top: 1px solid #E2E8F0; padding-top: 10px;">
                    <p class="footer-text" style="font-weight: 600; color: #1E293B;">{{ getSetting('system_title') }}</p>
                    <p class="footer-text">{{ localize('Email') }}: {{ getSetting('topbar_email') }} &bull; {{ localize('Phone') }}: {{ getSetting('navbar_contact_number') }} &bull; {{ env('APP_URL') }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
