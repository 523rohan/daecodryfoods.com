  <!--offer section start-->
      <section class="offer-section hm3-offer-section mb--80" data-background="{{ uploadedAsset(getSetting('organic_best_deal_banner')) }}">
          <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-5">
                      <div class="gshop-title text-center">
                          <h2 class="mb-3 text-white">
                              @php
                                $heading = localize(getSetting('organic_best_deal_banner_title'));
                                $heading = str_replace('{_', '<span class="bg-transparent meat-primary">', $heading);
                                $heading = str_replace('_}', '</span>', $heading);                            
                            @endphp
                           {!! $heading  !!}
                        </h2>
                          <p class="mb-0 text-white">{{(getSetting('organic_best_deal_banner_subtitle'))}}</p>
                      </div>

                       @php
                            $organic_best_deal_end_date = getSetting('organic_best_deal_end_date');
                            if (!is_null($organic_best_deal_end_date)) {
                                $organic_best_deal_end_date = date('m/d/Y H:i:s', strtotime($organic_best_deal_end_date));
                            }
                       @endphp                      
                    </div>
                </div>

              <div class="products-slider-wrapper mt-8 position-relative">
                  <div class="offer-product-slider swiper pb-6">
                      <div class="swiper-wrapper">
                             @php
                                 $products = \App\Models\Product::limit(3)->get();
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
                  <button class="ofp-slider-prev"><i class="fas fa-angle-left"></i></button>
                  <button class="ofp-slider-next"><i class="fas fa-angle-right"></i></button>
              </div>
          </div>
      </section>  <!--offer section end-->