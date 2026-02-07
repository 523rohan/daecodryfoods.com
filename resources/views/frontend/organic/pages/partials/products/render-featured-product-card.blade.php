@if($products->count() > 0)
    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-md-6 col-lg-4 col-xxl-3">
                @include('frontend.furniture.pages.partials.products.featured-product-card', [
                    'product' => $product,
                    'bgClass' => 'meat-card',
                ])
            </div>
        @endforeach
    </div>
@endif