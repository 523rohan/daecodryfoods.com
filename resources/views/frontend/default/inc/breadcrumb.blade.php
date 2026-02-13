<div class="gstore-breadcrumb position-relative z-1 overflow-hidden mt--50"
    style="padding-top: 100px; padding-bottom: 20px;">
    @include('frontend.default.inc.breadcrumbBgImages.' . getTheme())
    <div class="container">
        <div class="row">
            <div class="col-12">
                @yield('breadcrumb-contents')
            </div>
        </div>
    </div>
</div>