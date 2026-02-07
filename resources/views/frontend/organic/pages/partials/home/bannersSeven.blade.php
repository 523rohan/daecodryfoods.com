
   <!--call to action start-->
      <section class="cta-section pb-120">
                      @php
                        $banners = getSetting('organic_banner_section_seven_banners', 'json');
                        $banners = json_decode($banners, true);
                    @endphp
            @foreach ($banners ?? [] as $banner)
          <div class="container">
              <div class="cta-box rounded text-center" data-background="{{ uploadedAsset($banner['image']) ?? null}}">
                  <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 mb-2">
                      <h6 class="mb-0 text-secondary gshop-subtitle">{{$banner['subtitle'] ?? ''}}</h6>
                      <span>
                          <svg width="58" height="10" viewBox="0 0 58 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <line x1="-6.99382e-08" y1="5.2" x2="52" y2="5.2" stroke="#FF7C08" stroke-width="1.6"/>
                              <path d="M58 5L50.5 9.33013L50.5 0.669872L58 5Z" fill="#FF7C08"/>
                          </svg>   
                      </span>
                  </div>
                  <h3 class="mb-5">{{$banner['title'] ?? ''}}</h3>
                  <a href="{{$banner['link'] ?? ''}}" class="btn btn-secondary">Shop Now<span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
              </div>
          </div>
          @endforeach
      </section>  <!--call to action end-->