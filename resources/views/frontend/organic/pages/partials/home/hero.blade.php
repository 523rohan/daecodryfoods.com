
 {{-- @dd(getSetting('organic_hero_sliders')) --}}
  <!--hero section start-->

  
      <section class="healthcare-hero pt-7 pb-120 overflow-hidden">
          <div class="container">
              <div class="row g-5 g-xl-4 align-items-center justify-content-center">
                  <div class="col-xl-8">
                      <div class="healthcare-hero-slider-box overflow-hidden position-relative">
                           <div class="healthcare-hero-slider swiper">
                                <div class="swiper-wrapper">

                                  @php
                                    $sliders = getSetting('organic_hero_sliders') ? json_decode(getSetting('organic_hero_sliders') ?? '') : [];
                                  @endphp
                                 @foreach ($sliders as $slider)
                                    <div class="healthcare-hero-single swiper-slide bg-white rounded">
                                        <div class="left-content">
                                            <div class="d-flex align-items-center gap-1 gap-sm-2 mb-2 flex-wrap">
                                                <h5 class="mb-0 fw-normal text-primary gshop-subtitle">{{ $slider->sub_title ?? '' }}</h5>
                                                <span>
                                                    <svg width="138" height="16" viewBox="0 0 138 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <line y1="8" x2="132" y2="8" stroke="#4EB529" stroke-width="2"/>
                                                        <path d="M138 8L126 14.9282L126 1.0718L138 8Z" fill="#4EB529"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <h1 class="display-4 mb-3">{{ $slider->title ?? '' }}</h1>
                                            <p class="mb-6">{{$slider->text ?? ''}}</p>
                                            <a href="{{$slider->link ?? ''}}" class="btn btn-primary">Shop Now<span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                                        </div>
                                        <img src="{{ uploadedAsset($slider->image) ?? null }}" alt="orange" class="slide-product img-fluid d-none d-md-block">
                                        <img src="assets/img/products/orange-slice.png" alt="orange" class="slide-product img-fluid d-md-none">
                                    </div>
                                  @endforeach
                                
                               </div>
                          </div>
                          <div class="healthcare-hero-thumbnail-slider swiper">
                              <div class="swiper-wrapper">
                                 @foreach ($sliders as $slider)
                                    <div class="swiper-slide thumbnail-single">
                                        <img src="{{ uploadedAsset($slider->image) }}" alt="{{ $slider->title ?? '' }}" class="slide-product img-fluid d-none d-md-block">                                        
                                    </div>

                                  @endforeach
                              </div>
                          </div>
                         
                      </div>
                  </div>
                  <div class="col-xl-4 col-lg-6">
                     @php
                         $banners = getSetting('organic_banner_section_one_banners') ? json_decode(getSetting('organic_banner_section_one_banners')) : [];
                         @endphp
                         @foreach ($banners as $banner)
                               <div class="hm3-hero-banner position-relative overflow-hidden rounded-2" data-background="{{ uploadedAsset($banner->image) }}">
                              
                                        {{-- <span class="offer-badge text-white fw-bold fs-xxs bg-danger position-absolute rounded-0 start-0 top-0">-12% OFF</span> --}}
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <h6 class="gshop-subtitle text-danger fw-normal mb-0">{{ $banner->subtitle }}</h6>
                                            <span>
                                                <svg width="47" height="8" viewBox="0 0 47 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0 3.99999L43.3478 4" stroke="#FE4343" stroke-width="1.21739"/>
                                                    <path d="M47 4L41 0.535898L41 7.4641L47 4Z" fill="#FE4343"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <h3 class="mb-5 mt-2 text-white">{{ $banner->title }}</h3>
                                     <a href="{{ $banner->link }}" class="btn btn-secondary">Shop Now <span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                              </div>
                       @endforeach
                  </div>
              </div>
          </div>
      </section>  <!--hero section end-->

        
    