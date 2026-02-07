<div class="meat-card meat-card--row">
    <div class="meat-card__img text-center">
        <a href="javascript:void(0);" class="link d-inline-block text-center">
            <img src="{{ uploadedAsset($product->thumbnail_image) }}" alt="product"
                class="img-fluid w-100 h-100 object-fit-contain">
        </a>
    </div>
       
    
    <div class="product-overlay position-absolute start-0 top-0 w-100 h-100 d-flex align-items-center justify-content-center gap-1 rounded-2">
        @if (isLoggedIn() && isCustomer())
            <a href="javascript:void(0);" class="rounded-btn fs-xs" onclick="addToWishlist({{ $product->id }})"><i
                    class="fa-regular fa-heart"></i></a>
        @elseif(!isLoggedIn())
            <a href="javascript:void(0);" class="rounded-btn fs-xs" onclick="addToWishlist({{ $product->id }})"><i
                    class="fa-regular fa-heart"></i></a>
        @endif

        <a href="javascript:void(0);" class="rounded-btn fs-xs"
            onclick="showProductDetailsModal({{ $product->id }})"><i class="fa-solid fa-eye"></i></a>
    </div>

    <div class="card-content mt-4 mt-sm-0 w-100">
        <a
            href="{{ route('products.show', $product->slug) }}"
            class="fw-bold text-heading title fs-sm tt-line-clamp tt-clamp-1">
            {{ $product->collectLocalization('name') }}
        </a>
        <div class="pricing mt-2">
            @include('frontend.organic.pages.partials.products.pricing', [
                'product' => $product,
                'onlyPrice' => true,
            ])
        </div>

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
            <div class="d-flex justify-content-between align-items-center mt-10">
                <span class="flex-grow-1">
                    <a href="javascript:void(0);" class="fs-xs fw-bold mt-10 d-inline-block explore-btn"
                        onclick="showProductDetailsModal({{ $product->id }})">{{ localize('Shop Now') }}<span
                            class="ms-1"><i class="fa-solid fa-arrow-right"></i></span></a>
                </span>

                @if (getSetting('enable_reward_points') == 1)
                    <span class="fs-xxs fw-bold" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="{{ localize('Reward Points') }}">
                        <i class="fas fa-medal"></i> {{ $product->reward_points }}
                    </span>
                @endif


            </div>
        @else
            <form action="" class="direct-add-to-cart-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="product_variation_id" value="{{ $product->variations[0]->id }}">
                <input type="hidden" value="1" name="quantity">

                <div class="d-flex justify-content-between align-items-center mt-10">
                    <span class="flex-grow-1">
                        @if (!$isVariantProduct && $stock < 1)
                            <a href="javascript:void(0);" class="fs-xs fw-bold d-inline-block explore-btn">
                                {{ localize('Out of Stock') }}
                                <span class="ms-1"><i class="fa-solid fa-arrow-right"></i></span>
                            </a>
                        @else
                            <a href="javascript:void(0);" onclick="directAddToCartFormSubmit(this)"
                                class="fs-xs fw-bold d-inline-block explore-btn direct-add-to-cart-btn">
                                <span class="add-to-cart-text">{{ localize('Shop Now') }}</span>
                                <span class="ms-1"><i class="fa-solid fa-arrow-right"></i></span>
                            </a>
                        @endif
                    </span>

                    @if (getSetting('enable_reward_points') == 1)
                        <span class="fs-xxs fw-bold" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="{{ localize('Reward Points') }}">
                            <i class="fas fa-medal"></i> {{ $product->reward_points }}
                        </span>
                    @endif
                </div>
            </form>
        @endif

    </div>
</div>


        {{-- left --}}
    {{-- <div class="meat-card meat-card--row">
        <div class="meat-card__img text-center">
            <a href="#" class="link d-inline-block text-center">
                <img src="assets/img/home-7/feat-product-1.png" alt="image" class="img-fluid w-100 h-100 object-fit-contain">
            </a>
        </div>
        <div class="meat-card__body">
            <ul class="list list--row mb-2">
                <li>
                    <span class="meat-card__star d-inline-block fs-14">
                        <i class="fas fa-star"></i>
                    </span>
                </li>
                <li>
                    <span class="meat-card__star d-inline-block fs-14">
                        <i class="fas fa-star"></i>
                    </span>
                </li>
                <li>
                    <span class="meat-card__star d-inline-block fs-14">
                        <i class="fas fa-star"></i>
                    </span>
                </li>
                <li>
                    <span class="meat-card__star d-inline-block fs-14">
                        <i class="fas fa-star"></i>
                    </span>
                </li>
                <li>
                    <span class="meat-card__star d-inline-block fs-14">
                        <i class="fas fa-star"></i>
                    </span>
                </li>
            </ul>
            <h6 class="meat-card__title">
                <a href="#" class="link meat-card__title-link"> Bottel Gaurd (Lauki) </a>
            </h6>
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-2">
                <div class="d-flex align-items-center gap-3">
                    <h6 class="meat-card__pre-price text-decoration-line-through mb-0">
                        $95.00
                    </h6>
                    <h6 class="meat-card__current-price mb-0">$55.00</h6>
                </div>
            </div>
            <a href="#" class="link d-inline-flex text-center justify-content-center align-items-center fs-14 gap-2 clr-heading :clr-secondary">
                <span class="d-inline-block fw-semibold"> Shop Now </span>
                <span class="d-inline-block">
                    <i class="fas fa-arrow-right"></i>
                </span>
            </a>
        </div>
    </div> --}}