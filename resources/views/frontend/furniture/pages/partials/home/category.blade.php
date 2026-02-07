{{-- <section class="gshop-category-section bg-white pt-120 position-relative z-1 overflow-hidden"> --}}
    <div class="section-space-top section-space-sm-bottom">
        <div class="section-space-xsm-bottom">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-xl-6">                                                
                        <h2 class="text-center display-6 mb-0">
                            @php
                                $heading = localize(getSetting('furniture_top_categories_title'));
                                $heading = str_replace('{_', '<span class="meat-primary">', $heading);
                                $heading = str_replace('_}', '</span>', $heading);
                            @endphp
                            {!! $heading !!}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row g-4">
                <div class="col-12">
                    <ul class="list list--row flex-wrap gap-4 category-list">
                        @php
                            $furniture_top_category_ids = getSetting('furniture_top_category_ids') != null ? json_decode(getSetting('furniture_top_category_ids')) : [];
                            $categories = \App\Models\Category::whereIn('id', $furniture_top_category_ids)->get();
                        @endphp
                        
                        @foreach ($categories as $category)
                            @php
                                $productCount = CountProductsByCategoryAndThemeId(getThemeId('furniture'), $category->id);
                            @endphp


                            <li>
                                <a href="{{ route('products.index') }}?&category_id={{ $category->id }}"
                                    class="link d-block text-center category-list__content px-4 py-8 rounded-2">
                                    <span class="category-list__icon mb-6 d-block text-center">
                                        <img src="{{ uploadedAsset($category->collectLocalization('thumbnail_image')) }}"
                                            alt="" class="img-fluid category-list__icon-is transition">
                                    </span>
                                    <span class="category-list__title d-block fw-bold clr-heading text-center fs-20 mb-1 transition">
                                        {{ $category->collectLocalization('name') }}
                                    </span>
                                    <span class="category-list__subtitle d-flex justify-content-center align-items-center gap-1">
                                        <span class="category-list__dot transition d-inline-block flex-shrink-0 w-2 h-2 rounded-circle secondary-bg"></span>
                                        
                                        <span class="category-list__subtitle-text clr-heading fs-14 transition">
                                            {{ $productCount }} {{ localize(' Items Chair') }}
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endforeach                   
                    </ul>
                </div>
            </div>
        </div>
    </div>
{{-- </section> --}}
