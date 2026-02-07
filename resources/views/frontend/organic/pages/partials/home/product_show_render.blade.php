 @foreach ( $products as $product )
    <div class="horizontal-product-card d-sm-flex align-items-center p-3 bg-white rounded-2 mt-3 border gap-4">
        <div class="thumbnail position-relative rounded-2">
            <img src="{{ uploadedAsset($product->collectLocalization('thumbnail_image')) ?? null }}" alt="product" class="img-fluid">
            <div class="product-overlay position-absolute start-0 top-0 w-100 h-100 d-flex align-items-center justify-content-center gap-1 rounded-2">
                <a href="#" class="rounded-btn fs-xs"><i class="fa-regular fa-heart"></i></a>
                <a href="#" class="rounded-btn fs-xs"><i class="fa-solid fa-eye"></i></a>
                <a href="#" class="rounded-btn fs-xs">
                    <svg width="13" height="10" viewBox="0 0 13 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.86193 0.189422C9.62476 0.422214 9.62476 0.799637 9.86193 1.03243L10.6472 1.80311H7.25462C5.91292 1.80311 4.82521 2.87064 4.82521 4.18749V4.78359C4.82521 5.11281 5.09713 5.37968 5.43256 5.37968C5.768 5.37968 6.03991 5.11281 6.03991 4.78359V4.18749C6.03991 3.52906 6.58374 2.9953 7.25462 2.9953H10.6472L9.86193 3.76599C9.62476 3.99877 9.62476 4.37622 9.86193 4.60899C10.0991 4.84177 10.4837 4.84177 10.7208 4.60899L12.5429 2.82071C12.7801 2.58792 12.7801 2.2105 12.5429 1.9777L10.7208 0.189422C10.4837 -0.0433652 10.0991 -0.0433652 9.86193 0.189422ZM7.86197 4.18749C7.52653 4.18749 7.25462 4.45436 7.25462 4.78359V5.37968C7.25462 6.03813 6.7108 6.57187 6.03991 6.57187H2.64736L3.43261 5.80118C3.66979 5.5684 3.66979 5.19096 3.43261 4.95818C3.19542 4.72541 2.81087 4.72541 2.57368 4.95818L0.751618 6.74647C0.514435 6.97924 0.514435 7.35669 0.751618 7.58946L2.57368 9.37775C2.81087 9.61052 3.19542 9.61052 3.43261 9.37775C3.66979 9.14497 3.66979 8.76752 3.43261 8.53475L2.64736 7.76406H6.03991C7.38162 7.76406 8.46933 6.69651 8.46933 5.37968V4.78359C8.46933 4.45436 8.19742 4.18749 7.86197 4.18749Z" fill="#5D6374"></path>
                    </svg>  
                </a>
            </div>
        </div>
        <div class="card-content mt-4 mt-sm-0">
            <a href="{{route('products.show', ['slug'=>$product->slug])}}" class="fw-bold text-heading title d-block fs-sm"><h6 class="mb-0">
                {{$product->name ?? ''}}
            </h6>
        </a>
            <div class="pricing mt-2">
                @include('frontend.default.pages.partials.products.pricing', [
                    'product' => $product,
                    'onlyPrice' => true,
                ])
            </div>
            <a href="{{route('products.show', ['slug'=>$product->slug])}}" class="fs-xs fw-bold mt-10 d-inline-block explore-btn">Shop Now<span class="ms-1"><i class="fa-solid fa-arrow-right"></i></span></a>    
        </div>
    </div>
  @endforeach

{{-- <img src="{{ uploadedAsset($category->collectLocalization('thumbnail_image')) ?? null }}" 
    alt="{{ $category->collectLocalization('name') }}" 
    class="img-fluid">
</div> --}}