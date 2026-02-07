<div class="meat-card meat-card--secondary">
    <div class="meat-card__img text-center">
        <a href="{{ route('products.show', $product->slug) }}" class="link d-inline-block text-center">
            <img src="{{ uploadedAsset($product->thumbnail_image) }}" alt="{{ $product->collectLocalization('name') }}"
                class="img-fluid w-100 h-100 object-fit-contain">
        </a>
        <ul class="list gap-2 meat-card__icon-list">
            <li>                                                       
                <a href="javascript:void(0);" class="link d-grid place-content-center w-9 h-9 rounded-circle light-bg clr-heading :two-bg :clr-light drop-shadow">
                    <i class="fa-regular fa-heart" onclick="addToWishlist({{ $product->id }})"></i>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);" onclick="showProductDetailsModal({{ $product->id }})" class="link d-grid place-content-center w-9 h-9 rounded-circle light-bg clr-heading :two-bg :clr-light drop-shadow">
                    <i class="far fa-eye"></i>
                </a>
            </li>
            <li>
                <a href="#" class="link d-grid place-content-center w-9 h-9 rounded-circle light-bg clr-heading :two-bg :clr-light drop-shadow">
                    <i class="far fa-share-square"></i>
                </a>
            </li>
        </ul>
    </div>  

    <div class="meat-card__body">
        <span class="meat-card__subtitle">
            @if ($product->categories()->count() > 0)
                @foreach ($product->categories as $category)
                    <a href="{{ route('products.index') }}?&category_id={{ $category->id }}"
                        class="d-inline-block text-muted fs-xxs">{{ $category->collectLocalization('name') }}
                        @if (!$loop->last),@endif
                    </a>
                @endforeach
            @endif
        </span>
        <h6 class="meat-card__title">
            <a href="{{ route('products.show', $product->slug) }}" class="link meat-card__title-link">
                {{ $product->name }} {{ $product->id }}
            </a>
        </h6>
        
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-5">
            <div class="d-flex align-items-center gap-3">
                <h6 class="meat-card__current-price mb-0">
                    @include('frontend.default.pages.partials.products.pricing', [
                        'product' => $product,
                        'onlyPrice' => true,
                    ])
                </h6>
            </div>

            {{-- <a href="#" class="link meat-card__cart-btn"> Add to Cart </a> --}}
            {{-- add to card --}}
            @php
                $isVariantProduct = 0;
                $stock = 0;
                if ($product->variations()->count() > 1) {
                    $isVariantProduct = 1;
                } else {
                    $stock = $product->variations[0]->product_variation_stock ? $product->variations[0]->product_variation_stock->stock_qty : 0;
                }
            @endphp

            @if ($isVariantProduct)
                <a href="javascript:void(0);" class="btn btn-secondary d-block btn-md rounded-1"
                    onclick="showProductDetailsModal({{ $product->id }})">{{ localize('Add to Cart') }}</a>
            @else
                <form action="" class="direct-add-to-cart-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="product_variation_id" value="{{ $product->variations[0]->id }}">
                    <input type="hidden" value="1" name="quantity">

                    @if (!$isVariantProduct && $stock < 1)
                        <a href="javascript:void(0);" class="btn btn-secondary d-block btn-md rounded-1 w-100">
                            {{ localize('Out of Stock') }}</a>
                    @else
                        <a href="javascript:void(0);" onclick="directAddToCartFormSubmit(this)"
                            class="btn btn-secondary d-block btn-md rounded-1 w-100 direct-add-to-cart-btn add-to-cart-text">{{ localize('Add to Cart') }}</a>
                    @endif
                </form>
            @endif
        </div>
    </div>
</div>