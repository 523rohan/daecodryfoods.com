 
 <!--featured section start-->
      <section class="hm3-feature-section pt-120 pb-100">
          <div class="container">
              <div class="row g-4">
                  <div class="col-xxl-3 col-lg-4 col-md-6">
                      <div class="bg-white rounded py-5 px-4 mt-4">
                          <h6 class="fw-normal gshop-subtitle text-secondary mb-1">
                             @php
                                $subtitle = localize(getSetting('organic_feature_categories_subtitle') ?? '');
                                $subtitle = str_replace('{_', '<span class="meat-primary">', $subtitle);
                                $subtitle = str_replace('_}', '</span>', $subtitle);
                            @endphp
                            {!! $subtitle !!}
                        </h6>
                        
                          <h4 class="mb-4">
                             @php
                                $heading = localize(getSetting('organic_feature_categories_title') ?? '');
                                $heading = str_replace('{_', '<span class="meat-primary">', $heading);
                                $heading = str_replace('_}', '</span>', $heading);
                            @endphp

                            {!! $heading !!}

                          </h4>
                           @php
                                $organic_feature_category_ids = getSetting('organic_feature_category_ids') != null 
                                    ? json_decode(getSetting('organic_feature_category_ids')) 
                                    : [];
                                $categories = \App\Models\Category::whereIn('id', $organic_feature_category_ids)->get();
                            @endphp
                          <ul class="hm3-category-list mb-5">
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="{{ route('products.index') }}?&category_id={{ $category->id ?? ''}}">
                                            {{ $category->collectLocalization('name') ?? '' }}
                                        </a>
                                    </li>
                                @endforeach
                          </ul>
                            @php
                                $link = localize(getSetting('organic_feature_categories_link') ?? '');
                                $link = str_replace('{_', '<span class="meat-primary">', $link);
                                $link = str_replace('_}', '</span>', $link);
                            @endphp
                          </h4>

                          <a href="{{ route('products.index') }}" class="btn btn-primary btn-md">Show All<span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                      </div>
                  </div>
                  <div class="col-xxl-9 col-lg-8 col-md-6">
                      <div class="feature-right position-relative mt-6 mt-md-0">
                          <h6 class="mb-3 fs-md">Featured</h6>
                          <span class="hr-spacer mb-5"></span>
                          <button class="hm3_product_slider_prev hm3_product_slider_control"><i class="fas fa-arrow-left"></i></button>
                          <button class="hm3_product_slider_next hm3_product_slider_control"><i class="fas fa-arrow-right"></i></button>
                          <div class="hm3-featured-products-slider swiper pb-5">
                              <div class="swiper-wrapper">
                                @php
                                    $products = \App\Models\Product::query()->where('is_featured', true)->get();
                                @endphp

                                 @foreach ($products as $product)        
                                    <div class="vertical-product-card rounded-2 position-relative border-0 bg-white mb-8 swiper-slide
                                        @php
                                            if($product->categories()->count() > 0){ 
                                                foreach ($product->categories as $category) {
                                                echo $category->id .' ';
                                                }
                                            } @endphp">
                                        @include('frontend.default.pages.partials.products.trending-product-card', [
                                            'product' => $product,
                                        ])                                      
                                      
                                    </div>
                                @endforeach
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>  <!--featured section end-->