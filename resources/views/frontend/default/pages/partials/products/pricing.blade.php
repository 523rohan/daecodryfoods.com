@if (productBasePrice($product) == discountedProductBasePrice($product))
    {{-- Show only base price, no range --}}
    <span class="fw-bold h4 text-danger">{{ formatPrice(productBasePrice($product)) }}</span>
@else
    {{-- Show discounted base price only, no range --}}
    <span class="fw-bold h4 text-danger">{{ formatPrice(discountedProductBasePrice($product)) }}</span>

    @if (isset($br))
        <br>
    @endif

    @if (!isset($onlyPrice) || $onlyPrice == false)
        {{-- Show original base price as strikethrough --}}
        <span
            class="fw-bold h4 deleted text-muted {{ isset($br) ? '' : 'ms-1' }}">{{ formatPrice(productBasePrice($product)) }}</span>
    @endif
@endif

@if ($product->unit)
    <small>/{{ $product->unit->collectLocalization('name') }}</small>
@endif