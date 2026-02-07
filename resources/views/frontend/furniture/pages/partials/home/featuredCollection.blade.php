<div class="featured-section section-space-top section-space-sm-bottom">
    <div class="section-space-sm-bottom">
        <div class="container">
            <div class="row g-4 justify-content-lg-between align-items-center">
                <div class="col-lg-6">
                    {{-- <h2 class="mb-0 display-6">{{ getSetting('furniture_featured_title') ?? null }}</h2> --}}
                    <h2 class="mb-0 display-6">
                        @php
                            $heading = localize(getSetting('furniture_featured_title'));
                            $heading = str_replace('{_', '<span class="meat-primary">', $heading);
                            $heading = str_replace('_}', '</span>', $heading);
                        @endphp
                        {!! $heading !!}
                    </h2>
                </div>
                <div class="col-lg-6 col-xl-5">
                    <p class="mb-0">
                        {{ getSetting('furniture_featured_sub_title') ?? null }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row g-4">
            <div class="col-12">               
                <div class="list-group flex-row flex-wrap featured-nav gap-4 gap-md-6 mb-12" role="tablist">                    
                    @php
                       $category_ids = getSetting('furniture_featured_product_categories') != null ? json_decode(getSetting('furniture_featured_product_categories')) : [];
                       $categories = getCategoriesByIds($category_ids);
                    @endphp
                    {{-- @dd($categories); --}}
                    @foreach ($categories as $category)
                    @php
                        $productsCount = CountProductsByCategoryAndThemeId(getThemeId('furniture'), $category->id);

                    @endphp
                        <a class="link featured-nav__link fw-bold clr-heading {{ $loop->first ? 'active' : '' }}"
                            data-bs-toggle="list"
                            href="#" 
                            id="filterFeaturedCategoryProduct"
                            data-category_id="{{ $category->id }}"
                            data-theme_id="{{ getThemeId('furniture') }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="-1" role="tab">                            
                            {{ $category->name }}
                            <span class="featured-nav__link-count">{{ $productsCount ?? 0 }}</span>
                        </a>
                    @endforeach  
               
                    <a class="link d-inline-flex justify-content-center align-items-center gap-1 clr-heading :clr-secondary ms-md-auto" href="{{ route('products.index') }}">                        
                        <span class="d-inline-block fw-bold active" data-filter="*">{{ localize('All Furniture') }}</span>                        
                        <span class="d-inline-block clr-secondary">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </a>
                </div>

                <div class="tab-content featured-tab-content">
                    {{-- will load throw ajax --}}
                </div>                            
            </div>

            {{-- banner Three --}}
            <div class="col-lg-8">
                @foreach ($banners['banner_section_three_banners'] ?? [] as $banner)
                    <div class="product-banner">
                        <a href="{{ $banner->link }}" class="d-block">
                            <img src="{{ uploadedAsset($banner->image) }}" class="img-fluid w-100" alt="" srcset="">
                        </a>                       
                    </div>
                @endforeach
            </div>

            {{-- banner Four --}}
            <div class="col-lg-4">
                @foreach ($banners['banner_section_four_banners'] ?? [] as $banner)
                    <div class="product-banner">
                        <a href="{{ $banner->link }}" class="d-block">
                            <img src="{{ uploadedAsset($banner->image) }}" class="img-fluid w-100" alt="" srcset="">
                        </a>                       
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>