
      <!--banner section start-->
      <section class="banner-section">
          <div class="container">
              <div class="row g-4">

                  @php
                        $banners = getSetting('organic_banner_section_four_banners', 'json');
                        $banners = json_decode($banners, true);
                  @endphp
                    @foreach ($banners ?? [] as $banner)
                  <div class="col-xl-6">
                      <div class="banner-4 py-7 px-5 rounded-3" data-background="{{ uploadedAsset($banner['image']) ?? null }}">
                          <div class="d-flex align-items-center gap-2 mb-2">
                              <h6 class="mb-0 gshop-subtitle text-danger fw-normal">{{ $banner['subtitle'] ?? '' }}</h6>
                              <span>
                                  <svg width="47" height="8" viewBox="0 0 47 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M0 3.99999L43.3478 4" stroke="#FF0505" stroke-width="1.21739"></path>
                                      <path d="M47 4L41 0.535898L41 7.4641L47 4Z" fill="#FF0505"></path>
                                  </svg>    
                              </span>
                          </div>
                          <h3 class="mb-3 text-white">{{ $banner['title'] ?? '' }}</h3>
                          <div class="d-flex align-items-center gap-3">
                              <span class="badge-clip fw-medium text-white">HOT</span>
                              <span class="text-white fw-semibold fs-xs">{{ $banner['text'] ?? '' }}</span>
                          </div>
                          <a href="{{ $banner['link'] ?? '' }}" class="btn btn-secondary btn-md rounded-1 mt-5">Shop Now<span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                      </div>
                  </div>
                    @endforeach

                    @php
                            $banners = getSetting('organic_banner_section_five_banners', 'json');
                            $banners = json_decode($banners, true);
                    @endphp
                @foreach ($banners ?? [] as $banner)
                  <div class="col-xl-6">
                      <div class="banner-4 py-7 px-5 rounded-3" data-background="{{ uploadedAsset($banner['image']) ?? null }}">
                          <div class="d-flex align-items-center gap-2 mb-1">
                              <h6 class="mb-0 gshop-subtitle text-danger fw-normal">{{ $banner['subtitle'] ?? '' }}</h6>
                              <span>
                                  <svg width="47" height="8" viewBox="0 0 47 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M0 3.99999L43.3478 4" stroke="#FF0505" stroke-width="1.21739"></path>
                                      <path d="M47 4L41 0.535898L41 7.4641L47 4Z" fill="#FF0505"></path>
                                  </svg>    
                              </span>
                          </div>
                          <h3 class="mb-3">{{ $banner['title'] ?? '' }}</h3>
                          
                          <a href="{{ $banner['link'] ?? '' }}" class="btn btn-outline-secondary btn-md border-secondary rounded-1 mt-5">Shop Now<span class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                      </div>
                  </div>
                @endforeach
              </div>
          </div>
      </section>  <!--banner section end-->