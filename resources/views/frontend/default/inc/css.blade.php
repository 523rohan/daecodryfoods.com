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
    @media (min-width: 992px) {
        .footer-copyright .copyright-text {
            white-space: nowrap;
        }
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
    /* Hero Section Mobile Fix */
    @media (max-width: 991.98px) {
        .hero-img {
            position: relative !important;
            top: 0 !important;
            transform: none !important;
            right: 0 !important;
            margin-top: 30px;
            max-height: 300px;
            width: auto;
        }

        .hero-left-content {
            position: relative;
            z-index: 10;
            text-align: center;
        }

        .hero-btns {
            justify-content: center;
        }
    }

    /* login section mobile fix */
    @media (max-width: 1399.98px) {
        .login-section {
            align-items: flex-start; /* move to top */
            padding: 0 !important; /* remove padding */
        }
    }

    /* Mobile Header Cleanup */
    @media (max-width: 991.98px) {
        .gshop-navbar {
            padding-top: 5px !important;
            padding-bottom: 5px !important;
            border-radius: 0 !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05) !important;
        }
        
        .navbar-logo {
            max-height: 55px !important;
            width: auto !important;
            transform: scale(1.15);
            transform-origin: left center;
        }

        .gshop-offcanvas-btn {
            width: 40px !important;
            height: 40px !important;
            border-radius: 6px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
        }

        .gshop-offcanvas-btn svg {
            width: 18px !important;
            height: 18px !important;
        }
    }

    /* Desktop Header Cleanup */
    @media (min-width: 992px) {
        .gshop-navbar {
            padding-top: 8px !important;
            padding-bottom: 8px !important;
            margin-top: 10px !important;
        }

        .navbar-logo {
            max-height: 70px !important;
            width: auto !important;
            transform: scale(1.1);
            transform-origin: left center;
        }
    }
</style>
