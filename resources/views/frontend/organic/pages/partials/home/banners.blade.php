
<div class="row g-4 justify-content-center mt-1">
            @php
                $banners = getSetting('organic_banner_section_two_banners', 'json');
                $banners = json_decode($banners, true);
            @endphp
        @foreach ($banners as $banner)
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
    @foreach ($banners as $banner)
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