    <!-- Product Banner One-->
    <div class="section-space-sm-top section-space-bottom">
        <div class="container">
            <div class="row g-4">
                @foreach ($banners['banner_section_one_banners'] ?? [] as $banner)
                    <div class="col-lg-6">
                        <div class="product-banner">
                            <a href="{{ $banner->link }}" class="d-block">
                                <img src="{{ uploadedAsset($banner->image) }}" class="img-fluid w-100" alt="" srcset="">
                            </a>
                        </div>
                    </div>
                @endforeach


            {{-- Banner two  --}}
            <div class="row g-4">                
                @foreach ($banners['banner_section_two_banners'] ?? [] as $banner)
                    <div class="col-12 d-none d-md-block">
                        <div class="product-banner">
                            <a href="{{ $banner->link }}" class="d-block">
                                <img src="{{ uploadedAsset($banner->image) }}" class="img-fluid w-100" alt="" srcset="">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>