<!-- 3rd party -->
<link rel="stylesheet" href="{{ staticAsset('frontend/common/css/toastr.css') }}">
<!-- 3rd party -->
@if (isset($localLang) && $localLang->is_rtl == 1)
    <link rel="stylesheet" href="{{ staticAsset('frontend/default/assets/css/main-rtl.css') }}">
@else
    <link rel="stylesheet" href="{{ staticAsset('frontend/default/assets/css/main.css') }}">
@endif

<link rel="stylesheet" href="{{ staticAsset('frontend/common/css/select2.css') }}">
<link rel="stylesheet" href="{{ staticAsset('frontend/common/css/custom.css') }}">
<link rel="stylesheet" href="{{ staticAsset('frontend/common/css/summernote-lite.min.css') }}">
<link rel="stylesheet" href="{{ staticAsset('frontend/common/css/summernote-custom.css') }}">

<style>
    .gshop-navbar .logo {
        display: flex;
        align-items: center;
        min-height: 84px;
    }

    .gshop-navbar .navbar-logo {
        width: auto;
        max-width: 220px;
        max-height: 70px;
        object-fit: contain;
    }

    @media (min-width: 1200px) {
        .choose-us-section::after {
            background-image: url({{ uploadedAsset(getSetting('halal_why_choose_us_large_img')) }});
        }

        .on-sale-banner {
            background-image: url({{ uploadedAsset(getSetting('halal_on_sale_banner')) }});
        }
    }

    /* About page decorative shapes are already commented in Blade; keep them suppressed if a stale view still renders them. */
    .ab-about-section > img.position-absolute,
    .about-section > img.position-absolute {
        display: none !important;
    }
</style>
