

<!--feedback section start-->
      <section class="feedback-section pt-120 has-banner-bottom position-relative z-1 overflow-hidden bg-floral-white">
          <div class="container">
              <div class="row align-items-center g-4">
                  <div class="col-xl-7">
                      <div class="clients_thumb">
                          <img src="{{ asset('uploads/feedback/clients.png') }}" alt="clients" class="img-fluid">
                      </div>
                  </div>
                  <div class="col-xl-5">
                      <div class="swiper feedback-slider-2">
                          <div class="swiper-wrapper">
                                    @php
                                        $feedbacks = getSetting('organic_client_feedback', 'json');   
                                        $feedbacks = json_decode($feedbacks, true);
                                    @endphp
                                    
                         @foreach ($feedbacks ?? [] as $feedback)
                              <div class="swiper-slide feedback-card bg-white rounded py-6 px-4">
                                  <div class="d-flex align-items-center gap-4 flex-wrap mb-4">
                                      <img src="{{  uploadedAsset($feedback['image']) ?? null }}" alt="client" class="img-fluid rounded-circle flex-shrink-0">
                                      <div class="clients-info">
                                          <h5 class="mb-1">{{ $feedback['name'] ?? '' }}</h5>
                                          <ul class="d-flex align-items-center fs-xs text-warning">
                                               {{ renderStarRatingFrontOrganic($feedback['rating'] ?? '') }}
                                          </ul>
                                      </div>
                                  </div>
                                  <p class="mb-0">“{{ $feedback['review'] ?? '' }}”</p>
                              </div>
                            @endforeach
                          </div>
                          <div class="slider-arrows text-end mt-5">
                              <button type="button" class="fd2-arrow-left"><i class="fas fa-angle-left"></i></button>
                              <button type="button" class="fd2-arrow-right"><i class="fas fa-angle-right"></i></button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>