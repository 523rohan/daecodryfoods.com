<div class="section-space-sm-y three-bg">
    <div class="section-space-sm-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-xl-7">
                    <h2 class="display-6 text-center">
                        @php
                            $heading = localize(getSetting('furniture_featured_brand_title'));
                            $heading = str_replace('{_', '<span class="meat-primary">', $heading);
                            $heading = str_replace('_}', '</span>', $heading);
                        @endphp
                        {!! $heading !!}
                    </h2>

                    <p class="mb-0 text-center">
                        {{ getSetting('furniture_featured_brand_sub_title') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="section-space-sm-bottom">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <ul class="list gap-4 gap-xxl-8">
                        @php
                            $furniture_featured_brand_products_left = getSetting('furniture_featured_brand_products_left') != null ? json_decode(getSetting('furniture_featured_brand_products_left')) : [];                            
                            $left_products = getProductsByIds($furniture_featured_brand_products_left);                                
                        @endphp

                        
                        @foreach ($left_products as $product)
                            <li>
                                <div class="{{ !$loop->last ? 'mb-3' : '' }}">
                                    @include('frontend.furniture.pages.partials.products.horizontal-product-card', [
                                        'product' => $product,
                                        'bgClass' => 'bg-white',
                                    ])
                                </div>
                                
                            </li>
                        @endforeach
                    </ul>
                </div>

                 <!-- banner -->
                 <div class="col-md-6 col-lg-4 order-md-3 order-xl-2">
                    <div class="product-banner px-xxl-4">
                        <a href="{{ getSetting('furniture_featured_brand_banner_link') }}" class="my-auto">
                            <img src="{{ uploadedAsset(getSetting('furniture_featured_brand_center_banner')) }}" alt="">                            
                        </a>
                    </div>
                </div>
                {{-- ============================================== --}}
                {{-- Banner Five end --}}
                {{-- ============================================== --}}

                <div class="col-md-6 col-lg-4 order-md-2 order-xl-3">
                    <ul class="list gap-4 gap-xxl-8">
                        @php
                            $furniture_featured_brand_products_right = getSetting('furniture_featured_brand_products_right') != null ? json_decode(getSetting('furniture_featured_brand_products_right')) : [];                            
                            $right_products = getProductsByIds($furniture_featured_brand_products_right);                                
                        @endphp

                        @foreach ($right_products as $product)                        
                            <li>
                                <div class="{{ !$loop->last ? 'mb-3' : '' }}">
                                    @include('frontend.furniture.pages.partials.products.horizontal-product-card', [
                                        'product' => $product,
                                        'bgClass' => 'bg-white',
                                    ])
                                </div>                            
                            </li>
                        @endforeach                       
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    {{-- ============================================== --}}
    {{-- Banner Six start --}}
    {{-- ============================================== --}}

    <div class="container">
        <div class="row">
            @foreach ($banners['banner_section_six_banners'] ?? [] as $banner)
                <div class="col-12">
                    <div class="product-banner">
                        <a href="{{ $banner->link }}" class="d-block">
                            <img src="{{ uploadedAsset($banner->image) }}" class="img-fluid w-100" alt="">
                        </a>                          
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {{-- ============================================== --}}
    {{-- Banner Six End --}}
    {{-- ============================================== --}}



</div>