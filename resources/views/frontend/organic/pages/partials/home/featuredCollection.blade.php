        <section class="pt-120">
            <div class="container">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <div class="hm3-product-tabs bg-white rounded py-6 px-5">
                            <ul class="nav nav-tabs hm3-tab-nav" role="tablist">
                                <li><a href="#new_arrivals" class="active" data-bs-toggle="tab" aria-selected="true" role="tab">New Arrivals</a></li>
                                <li><a href="#trending_products" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Trending Products</a></li>
                                <li><a href="#best_selling" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Best Selling</a></li>
                            </ul>
                            <div class="tab-content mt-5">
                                <div class="tab-pane fade show active" id="new_arrivals" role="tabpanel">                                                                        
                                    @if ($filteredProducts['new_arrival_products'])
                                        @foreach ($filteredProducts['new_arrival_products'] ?? [] as $product)
                                            <div class="{{ !$loop->last ? 'mb-3' : '' }}">
                                                @include('frontend.default.pages.partials.products.horizontal-product-card', [
                                                    'product' => $product,
                                                    'bgClass' => 'bg-white',
                                                ])
                                            </div>    
                                        @endforeach
                                    @endif                                    
                                </div>

                                <div class="tab-pane fade" id="trending_products" role="tabpanel">
                                    @if ($filteredProducts['trending_products'])
                                        @foreach ($filteredProducts['trending_products'] ?? [] as $product)
                                            <div class="{{ !$loop->last ? 'mb-3' : '' }}">
                                                @include('frontend.default.pages.partials.products.horizontal-product-card', [
                                                    'product' => $product,
                                                    'bgClass' => 'bg-white',
                                                ])
                                            </div>                                            
                                        @endforeach
                                    @endif  
                                </div>
                                
                                <div class="tab-pane fade" id="best_selling" role="tabpanel">
                                    @if ($filteredProducts['best_selling'])
                                        @foreach ($filteredProducts['best_selling'] ?? [] as $product)
                                            <div class="{{ !$loop?->last ? 'mb-3' : '' }}">
                                                @include('frontend.default.pages.partials.products.horizontal-product-card', [
                                                    'product' => $product,
                                                    'bgClass' => 'bg-white',
                                                ])
                                            </div>    
                                        @endforeach
                                    @endif  
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-6">
                        <div class="section-right pt-8">
                            @php
                                $banners = getSetting('organic_banner_section_six_banners', 'json') ?? '';
                                $banners = json_decode($banners, true);
                                $deal = $banners[0] ?? '';
                                $banner = $banners[1] ?? '';
                            @endphp

                            <div class="section-title mx-400">
                                @php                                    
                                    $heading = str_replace('{_', '<span class="bg-transparent meat-primary">', $deal['title'] ?? '');
                                    $heading = str_replace('_}', '</span>', $heading);
                                @endphp

                                <h2 class="mb-3">{!! $heading ?? '' !!}</h2>

                                <p class="mb-0">{{ $deal['subtitle'] ?? '' }}</p>    
                            </div>
                            
                            <div class="row g-4 justify-content-center mt-3">
                                <div class="col-xl-4 col-md-4 col-sm-6">
                                  <div class="bg-white rounded py-6 px-3 text-center">
                                      <span class="py-3 px-4 bg-glimpse-pink d-inline-block rounded">
                                          <img src="{{ uploadedAsset($banner['firstCartImage'] ?? '' ) ?? null }}" alt="hand" class="img-fluid">
                                      </span>
                                      <h3 class="mt-4 mb-0"><span class="counter">{{ $deal['firstCardClientCount'] ?? '' }}</span><span>k+</span></h3>
                                      <p class="mb-0 fs-sm">{{ $deal['firstCardText'] ?? '' }}</p>
                                  </div>
                                </div>

                                <div class="col-xl-4 col-md-4 col-sm-6">
                                  <div class="bg-white rounded py-6 px-3 text-center">
                                      <span class="py-3 px-4 bg-glimpse-pink d-inline-block rounded">
                                          <img src="{{ uploadedAsset($banner['secondCartImage'] ?? '') }}" alt="{{ $deal['thirdCardText'] ?? '' }} " class="img-fluid">
                                      </span>
                                      <h3 class="mt-4 mb-0"><span class="counter">{{ $deal['secondCardClientCount'] ?? '' }}</span><span>k+</span></h3>
                                      <p class="mb-0 fs-sm">{{ $deal['secondCardText'] ?? '' }}</p>
                                  </div>
                                </div>

                                <div class="col-xl-4 col-md-4 col-sm-6">
                                  <div class="bg-white rounded py-6 px-3 text-center">
                                      <span class="py-3 px-4 bg-glimpse-pink d-inline-block rounded">
                                          <img src="{{ uploadedAsset($banner['thirdCartImage'] ?? '') }}" alt="{{ $deal['thirdCardText'] ?? '' }} " class="img-fluid">
                                      </span>
                                      <h3 class="mt-4 mb-0"><span class="counter">{{ $deal['thirdCardClientCount'] ?? '' }}</span><span>k+</span></h3>
                                      <p class="mb-0 fs-sm">{{ $deal['thirdCardText'] ?? '' }}</p>
                                  </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>