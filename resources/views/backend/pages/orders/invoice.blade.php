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
        .wrapper { width: 100%; table-layout: fixed; background-color: #F8FAFC; padding: 40px 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #FFFFFF; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
        
        /* HEADER */
        .header { padding: 40px 40px 30px; text-align: center; border-bottom: 1px solid #F1F5F9; }
        .logo { margin: 0 auto 20px; max-height: 48px; width: auto; }
        .header-title { font-size: 24px; font-weight: 800; color: #1E293B; letter-spacing: -0.02em; }
        .order-meta { font-size: 14px; color: #64748B; margin-top: 8px; }
        
        /* CONTENT */
        .content { padding: 40px; }
        
        /* INFO GRID */
        .section-title { font-size: 12px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 12px; }
        .info-card { background-color: #F8FAFC; border-radius: 8px; padding: 20px; margin-bottom: 30px; }
        .info-text { font-size: 14px; color: #334155; line-height: 1.6; }
        .info-name { font-weight: 700; color: #1E293B; margin-bottom: 4px; display: block; }
        
        /* ITEMS TABLE */
        .items-header { border-bottom: 2px solid #F1F5F9; padding-bottom: 12px; margin-bottom: 16px; }
        .item-row { border-bottom: 1px solid #F1F5F9; padding: 16px 0; }
        .item-name { font-size: 15px; font-weight: 600; color: #1E293B; margin-bottom: 4px; }
        .item-variation { font-size: 13px; color: #64748B; }
        .item-price { font-size: 14px; color: #475569; font-weight: 500; }
        
        /* TOTALS */
        .totals-container { margin-top: 30px; padding-top: 20px; border-top: 2px solid #F1F5F9; }
        .total-item { margin-bottom: 8px; font-size: 14px; color: #64748B; }
        .total-value { font-weight: 600; color: #334155; float: right; }
        .grand-total { margin-top: 16px; padding-top: 16px; border-top: 1px solid #F1F5F9; font-size: 18px; font-weight: 800; color: #1E293B; }
        .grand-total-value { float: right; color: #10B981; } /* Premium Green for total */
        
        /* FOOTER */
        .footer { padding: 40px; text-align: center; background-color: #F1F5F9; }
        .footer-text { font-size: 13px; color: #64748B; line-height: 1.6; }
        
        /* RESPONSIVE */
        @media screen and (max-width: 600px) {
            .wrapper { padding: 20px 10px; }
            .content { padding: 25px; }
            .header { padding: 30px 25px 20px; }
            .info-col { width: 100% !important; display: block; margin-bottom: 20px; }
        }
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
                                        @php $shippingAddress = $order->orderGroup->shippingAddress; @endphp
                                        {{ optional($shippingAddress)->address }}<br>
                                        {{ optional(optional($shippingAddress)->city)->name }}, {{ optional(optional($shippingAddress)->state)->name }}<br>
                                        {{ optional($shippingAddress)->pincode }}<br>
                                        {{ optional(optional($shippingAddress)->country)->name }}
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
                
                @foreach ($order->orderItems as $item)
                    @php $product = $item->product_variation->productWithTrashed; @endphp
                    <div class="item-row">
                        <table width="100%">
                            <tr>
                                <td>
                                    <div class="item-name">{{ $product->collectLocalization('name') }}</div>
                                    @php $variations = generateVariationOptions($item->product_variation->combinations); @endphp
                                    @if(count($variations) > 0)
                                        <div class="item-variation">
                                            @foreach ($variations as $variation)
                                                {{ $variation['name'] }}: @foreach ($variation['values'] as $value) {{ $value['name'] }} @endforeach{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </div>
                                    @endif
                                    <div style="font-size: 13px; color: #94A3B8; margin-top: 4px;">{{ localize('Qty') }}: {{ $item->qty }} &times; {{ formatPrice($item->unit_price) }}</div>
                                </td>
                                <td align="right" style="vertical-align: middle;">
                                    <div class="item-price">{{ formatPrice($item->total_price) }}</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                @endforeach

                <!-- TOTALS -->
                <div class="totals-container">
                    <div class="total-item">{{ localize('Subtotal') }} <span class="total-value">{{ formatPrice($order->orderGroup->sub_total_amount) }}</span></div>
                    @if($order->orderGroup->total_tips_amount > 0)
                        <div class="total-item">{{ localize('Tips') }} <span class="total-value">{{ formatPrice($order->orderGroup->total_tips_amount) }}</span></div>
                    @endif
                    <div class="total-item">{{ localize('Shipping') }} <span class="total-value">{{ formatPrice($order->orderGroup->total_shipping_cost) }}</span></div>
                    @if ($order->orderGroup->total_coupon_discount_amount > 0)
                        <div class="total-item" style="color: #EF4444;">{{ localize('Coupon Discount') }} <span class="total-value">-{{ formatPrice($order->orderGroup->total_coupon_discount_amount) }}</span></div>
                    @endif
                    @php
                        $isTaxInclusive = getSetting('taxes_inclusive') == '1';
                    @endphp
                    <div class="total-item">{{ localize('Tax') }}{{ $isTaxInclusive ? ' (' . localize('Included') . ')' : '' }} <span class="total-value">{{ formatPrice($order->orderGroup->total_tax_amount) }}</span></div>
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
                <p class="footer-text"><strong>{{ localize('Thank you for your order!') }}</strong></p>
                <p class="footer-text">{{ getSetting('invoice_thanksgiving') }}</p>
                <div style="margin-top: 20px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                    <p class="footer-text" style="font-weight: 600; color: #1E293B;">{{ getSetting('system_title') }}</p>
                    <p class="footer-text">{{ localize('Email') }}: {{ getSetting('topbar_email') }} &bull; {{ localize('Phone') }}: {{ getSetting('navbar_contact_number') }}</p>
                    <p class="footer-text">{{ env('APP_URL') }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
