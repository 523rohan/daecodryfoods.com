    	<!-- Hero -->
    	<div class="hero-7">
    		<div class="swiper hero-7__slider">
    			<div class="swiper-wrapper">
                    @foreach ($sliders as $slider)
                        <div class="swiper-slide">
                            <div class="hero-7__slider-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="hero-7__slider-inner">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <h1 class="hero-7__slider-title mb-12">
                                                            {{ $slider->title ?? '' }}
                                                            <span class="d-inline-block clr-primary text-decoration">
                                                                {{ $slider->sub_title ?? '' }}
                                                                <svg
                                                                    width="350"
                                                                    height="18"
                                                                    class="text-decoration__img"
                                                                    viewBox="0 0 350 18"
                                                                    fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                >
                                                                    <path
                                                                        d="M0.760582 17.8078C58.2735 11.9812 115.591 6.4118 174.037 5.89076C231.128 5.36082 287.619 7.7998 344.092 12.953C350.861 13.5145 350.908 6.40578 344.14 5.71507C286.505 0.554238 227.686 -0.995243 169.616 1.33782C113.094 3.55181 55.3997 7.56765 0.569619 17.4188C0.181752 17.5455 0.373564 17.8052 0.760582 17.8078Z"
                                                                    />
                                                                </svg>
                                                            </span>
                                                        </h1>
                                                        <p class="hero-7__slider-para fs-18 mb-12 text-wrap-balance">
                                                            {{ $slider->text ?? '' }}
                                                        </p>
                                                        <a href="{{ $slider->link }}" class="link button button-effect button-effect--primary">
                                                            <span class="d-inline-block fw-semibold"> Shop Now </span>
                                                            <span class="d-inline-block">
                                                                <i class="fas fa-arrow-right"></i>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="hero-7__slider-img d-none d-lg-block">                                    
                                    <img src="{{ uploadedAsset($slider->image) }}" alt=""
                                        class="img-fluid w-100 h-100 object-fit-contain">
                                </div>
                            </div>
                        </div>
                    @endforeach

    			</div>
    		</div>
    		<div class="swiper-pagination hero-7__pagination"></div>
    	</div>
    	<!-- /Hero End-->



{{-- Old slider here--}}
{{-- <section class="hero-7 gshop-hero pt-120 bg-white position-relative z-1 overflow-hidden">
    <img src="{{ staticAsset('frontend/default/assets/img/shapes/leaf-shadow.png') }}" alt="leaf"
        class="position-absolute leaf-shape z--1 rounded-circle d-none d-lg-inline">
    <img src="{{ staticAsset('frontend/default/assets/img/shapes/mango.png') }}" alt="mango"
        class="position-absolute mango z--1" data-parallax='{"y": -120}'>

    <img src="{{ staticAsset('frontend/default/assets/img/shapes/hero-circle-sm.png') }}" alt="circle"
        class="position-absolute hero-circle circle-sm z--1 d-none d-md-inline">

    <div class="container">
        <div class="gshop-hero-slider swiper">
            <div class="swiper-wrapper">
                @foreach ($sliders as $slider)
                    <div class="swiper-slide gshop-hero-single">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-xl-5 col-lg-7">
                                <div class="hero-left-content">
                                    <span
                                        class="gshop-subtitle fs-5 text-secondary mb-2 d-block">{{ $slider->sub_title }}</span>
                                    <h1 class="display-4 mb-3">{{ $slider->title }}</h1>
                                    <p class="mb-5 fs-6">{{ $slider->text }}</p>

                                    <div class="hero-btns d-flex align-items-center gap-3 gap-sm-5 flex-wrap">
                                        <a href="{{ $slider->link }}"
                                            class="btn btn-secondary">{{ localize('Explore Now') }}<span
                                                class="ms-2"><i class="fa-solid fa-arrow-right"></i></span></a>
                                        <a href="{{ route('home.pages.aboutUs') }}"
                                            class="btn btn-primary">{{ localize('About Us') }}<span class="ms-2"><i
                                                    class="fa-solid fa-arrow-right"></i></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-5">
                                <div class="hero-right text-center position-relative z-1 mt-6 mt-xl-0">

                                    <img src="{{ uploadedAsset($slider->image) }}" alt=""
                                        class="img-fluid position-absolute end-0 top-50 hero-img">

                                    <img src="{{ staticAsset('frontend/default/assets/img/shapes/hero-circle-lg.png') }}"
                                        alt="circle shape" class="img-fluid hero-circle">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @if(getSetting('facebook_link') || getSetting('twitter_link') || getSetting('linkedin_link') || getSetting('youtube_link'))
        <div class="gs-hero-social">
            <ul class="list-unstyled">
                @if(getSetting('facebook_link'))
                    <li><a href="{{ getSetting('facebook_link') }}"><i class="fab fa-facebook-f"></i></a></li>
                @endif
                @if(getSetting('twitter_link'))
                    <li><a href="{{ getSetting('twitter_link') }}"><i class="fab fa-twitter"></i></a></li>
                @endif
                @if(getSetting('linkedin_link'))
                    <li><a href="{{ getSetting('linkedin_link') }}"><i class="fab fa-linkedin-in"></i></a></li>
                @endif
                @if(getSetting('youtube_link'))
                    <li><a href="{{ getSetting('youtube_link') }}"><i class="fab fa-youtube"></i></a></li>
                @endif

            </ul>
            <span class="title fw-medium">{{localize('Follow on')}}</span>
        </div>
    @endif

    <div class="gshop-hero-slider-pagination theme-slider-control position-absolute top-50 translate-middle-y z-5">
    </div>
</section>  --}}
