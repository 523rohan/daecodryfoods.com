
 <!--category section start-->
      <section class="category-section">
          <div class="container">
              <div class="row justify-content-center">
                  <div class="col-xl-6">
                      <div class="section-title text-center">
                          <h2 class="mb-2">
                                @php
                                    $heading = localize(getSetting('organic_top_categories_title') ?? '');
                                    $heading = str_replace('{_', '<span class="meat-primary">', $heading);
                                    $heading = str_replace('_}', '</span>', $heading);
                                @endphp
                                {!! $heading !!}
                          </h2>
                          <p class="mb-0">
                             @php
                                    $heading = localize(getSetting('organic_top_categories_subtitle') ?? '');
                                    $heading = str_replace('{_', '<span class="meat-primary">', $heading);
                                    $heading = str_replace('_}', '</span>', $heading);
                                @endphp
                                {!! $heading !!}</p>
                      </div>
                  </div>
              </div>
               <div class="row justify-content-center g-4 mt-4">
                    @php
                        $organic_top_category_ids = getSetting('organic_top_category_ids') != null 
                            ? json_decode(getSetting('organic_top_category_ids')) 
                            : [];
                        $categories = \App\Models\Category::whereIn('id', $organic_top_category_ids)->get();
                    @endphp
                
                    @foreach ($categories ?? [] as $index => $category)
                    @php
                        $productCount = CountProductsByCategoryAndThemeId(getThemeId('organic'), $category->id);
                    @endphp

                    <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="gshop-animated-iconbox py-5 px-4 text-center border rounded-3 position-relative overflow-hidden">
                            <div class="animated-icon d-inline-flex align-items-center justify-content-center rounded-circle position-relative">
                                <img src="{{ uploadedAsset($category->collectLocalization('thumbnail_image')) ?? null }}" alt="flower" class="img-fluid">
                            </div>
                            <a href="{{ route('products.index') }}?&category_id={{ $category->id ?? ''}}" class="text-dark fs-sm fw-bold d-block mt-3">{{ $category->collectLocalization('name') }}</a>
                            <span class="total-count position-relative ps-3 fs-sm fw-medium doted-primary"> {{ $productCount }} {{ localize('Items') ?? ''}}</span>
                            <a href="{{ route('products.index') }}?&category_id={{ $category->id ?? ''}}" class="explore-btn position-absolute"><i class="fa-solid fa-arrow-up"></i></a>
                        </div>
                    </div>
                        @endforeach
                </div>
              <div class="row g-4 justify-content-center mt-1">
                     @php
                    $banners = getSetting('organic_banner_section_two_banners', 'json');
                    $banners = json_decode($banners, true);
                    @endphp
                @foreach ($banners ?? [] as $banner)
                  <div class="col-xl-4">
                      <div class="hm3-banner-2 rounded-3" data-background="{{ uploadedAsset($banner['image']) ?? null }}">
                          <div class="d-flex align-items-center flex-wrap gap-2">
                              <span class="gshop-subtitle text-primary">{{ $banner['subtitle'] ?? '' }}</span>
                              <span>
                                  <svg width="58" height="10" viewBox="0 0 58 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <line x1="-6.99382e-08" y1="5.2" x2="52" y2="5.2" stroke="#4EB529" stroke-width="1.6"/>
                                      <path d="M58 5L50.5 9.33013L50.5 0.669872L58 5Z" fill="#4EB529"/>
                                  </svg>
                              </span>
                          </div>
                          <h3 class="mt-2 mb-5">{{ $banner['title'] ?? '' }}</h3>
                          <a href="{{ $banner['link'] ?? ''}}" class="btn btn-primary btn-md">Shop Now <span class="ms-1"><i class="fas fa-arrow-right"></i></span></a>
                      </div>
                  </div>
                @endforeach

                    @php
                            $banners = getSetting('organic_banner_section_three_banners', 'json');
                            $banners = json_decode($banners, true);
                    @endphp
                    @foreach ($banners ?? [] as $banner)
                    <div class="col-xl-8">
                        <div class="hm3-banner-3 rounded-3" data-background="{{uploadedAsset($banner['image']) ?? null}}">
                            <div class="banner-content">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <h6 class="mb-0 text-secondary gshop-subtitle fw-normal">{{ $banner['subtitle'] ?? ''}}</h6>
                                    <span>
                                        <svg width="58" height="10" viewBox="0 0 58 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <line x1="-6.99382e-08" y1="5.2" x2="52" y2="5.2" stroke="#FF7C08" stroke-width="1.6"/>
                                            <path d="M58 5L50.5 9.33013L50.5 0.669872L58 5Z" fill="#FF7C08"/>
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-2 mb-3 text-white">{{ $banner['title'] ?? ''}}</h3>
                                <p class="mb-5 text-white">{{ $banner['text'] ?? ''}}</p>
                                <a href="{{ $banner['link'] ?? '' }}" class="btn btn-secondary btn-md">Shop Now<span class="ms-1"><i class="fas fa-arrow-right"></i></span></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
              </div>
          </div>
      </section>  <!--category section end-->