 <!-- Why Choose Us section start-->
      <section class="about-us-section pb-120 hm3-about-section overflow-hidden">
          <div class="container">

            
              <div class="row g-4 align-items-center">
                  <div class="col-xl-5">
                      <div class="about-us-left position-relative">
                         <img src="{{ uploadedAsset(getSetting('organic_why_choose_us_large_img') ?? null )}}" alt=" {{getSetting('organic_why_choose_us_title') ?? ''}}" class="img-fluid">

                          <div class="exp-box p-3 bg-white rounded-circle position-absolute">
                              <div class="bg-secondary w-100 h-100 rounded-circle d-flex align-items-center justify-content-center flex-column">
                                  <h2 class="text-white">
                                    {{getSetting('organic_why_choose_us_years_of_experience') ?? ''}}
                                </h2>
                                  <span class="h6 text-white">Year's Experience</span>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-xl-7">
                      <div class="about-us-right">
                          <div class="section-title-mx mb-6">
                              <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                  <h6 class="mb-0 gshop-subtitle text-secondary">{{getSetting('organic_why_choose_us_title') ?? ''}}</h6>
                                  <span>
                                      <svg width="58" height="10" viewBox="0 0 58 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <line x1="-6.99382e-08" y1="5.2" x2="52" y2="5.2" stroke="#FF7C08" stroke-width="1.6"/>
                                          <path d="M58 5L50.5 9.33013L50.5 0.669872L58 5Z" fill="#FF7C08"/>
                                      </svg>
                                  </span>
                              </div>
                              <h2 class="mb-3">{{getSetting('organic_why_choose_us_subtitle') ?? ''}}</h2>
                              <p class="mb-0">{{getSetting('organic_why_choose_us_text') ?? ''}}</p>
                          </div>
                          <div class="row g-3">
                              <div class="col-xl-6 col-lg-4 col-md-6">
                                  <div class="horizontal-icon-box d-flex align-items-center gap-4 bg-white rounded p-4 hover-shadow flex-wrap flex-xxl-nowrap">
                                      <span class="icon-wrapper position-relative flex-shrink-0">
                                          <img src="{{ uploadedAsset(getSetting('organic_why_choose_us_feature_one_icon') ?? null )}}" alt="{{getSetting('organic_why_choose_us_feature_one_title') ?? ''}}" class="img-fluid">
                                      </span>
                                      <div class="content-right">
                                          <h5 class="mb-3">{{getSetting('organic_why_choose_us_feature_one_title') ?? ''}}</h5>
                                          <p class="mb-0">{{getSetting('organic_why_choose_us_feature_one_text') ?? ''}}</p>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-xl-6 col-lg-4 col-md-6">
                                  <div class="horizontal-icon-box d-flex align-items-center gap-4 bg-white rounded p-4 hover-shadow flex-wrap flex-xxl-nowrap">
                                      <span class="icon-wrapper position-relative flex-shrink-0">
                                          <img src="{{ uploadedAsset(getSetting('organic_why_choose_us_feature_two_icon') ?? null )}}" alt="{{getSetting('organic_why_choose_us_feature_two_title') ?? ''}}" class="img-fluid">
                                      </span>
                                      <div class="content-right">
                                          <h5 class="mb-3">{{getSetting('organic_why_choose_us_feature_two_title') ?? ''}}</h5>
                                          <p class="mb-0">{{getSetting('organic_why_choose_us_feature_two_text') ?? ''}}</p>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-xl-6 col-lg-4 col-md-6">
                                  <div class="horizontal-icon-box d-flex align-items-center gap-4 bg-white rounded p-4 hover-shadow flex-wrap flex-xxl-nowrap">
                                      <span class="icon-wrapper position-relative flex-shrink-0">
                                          <img src="{{ uploadedAsset(getSetting('organic_why_choose_us_feature_three_icon') ?? null )}}" alt="{{getSetting('organic_why_choose_us_feature_three_title') ?? ''}}" class="img-fluid">
                                      </span>
                                      <div class="content-right">
                                          <h5 class="mb-3">{{getSetting('organic_why_choose_us_feature_three_title') ?? ''}}</h5>
                                          <p class="mb-0">{{getSetting('organic_why_choose_us_feature_three_text') ?? ''}}</p>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-xl-6 col-lg-4 col-md-6">
                                  <div class="horizontal-icon-box d-flex align-items-center gap-4 bg-white rounded p-4 hover-shadow flex-wrap flex-xxl-nowrap">
                                      <span class="icon-wrapper position-relative flex-shrink-0">
                                          <img src="{{ uploadedAsset(getSetting('organic_why_choose_us_feature_fourth_icon') ?? null )}}" alt="hand icon" class="img-fluid">
                                      </span>
                                      <div class="content-right">
                                          <h5 class="mb-3">{{getSetting('organic_why_choose_us_feature_fourth_title') ?? ''}}</h5>
                                          <p class="mb-0">{{getSetting('organic_why_choose_us_feature_fourth_text') ?? ''}}</p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section> 
       <!--Why Choose Us section end-->