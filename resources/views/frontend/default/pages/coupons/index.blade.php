@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Coupons') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('breadcrumb-contents')
    <div class="breadcrumb-content">
        <h2 class="mb-2 text-center">{{ localize('All Coupons') }}</h2>
        <nav>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item fw-bold" aria-current="page"><a
                        href="{{ route('home') }}">{{ localize('Home') }}</a></li>
                <li class="breadcrumb-item fw-bold" aria-current="page">{{ localize('Coupons') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents')
    <!--breadcrumb-->
    @include('frontend.default.inc.breadcrumb')
    <!--breadcrumb-->

    <style>
        .tt-coupon-single {
            border-radius: 24px;
            background: #ffffff;
            border: 1px solid #f0f0f0;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
            position: relative;
        }

        .tt-coupon-single:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        }

        .coupon-banner-wrap {
            width: 100%;
            height: 200px;
            padding: 30px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .coupon-banner-wrap img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .tt-coupon-single:hover .coupon-banner-wrap img {
            transform: scale(1.05);
        }

        .perforation-line {
            height: 0;
            border-top: 3px dashed #f0f0f0;
            position: relative;
            margin: 0 20px;
        }

        /* The "Ticket" cut-outs on the sides */
        .perforation-line::before,
        .perforation-line::after {
            content: '';
            position: absolute;
            top: -12px;
            /* Half of width */
            width: 24px;
            height: 24px;
            background: #f9fbff;
            /* Match section background */
            border-radius: 50%;
            border: 1px solid #f0f0f0;
            z-index: 2;
        }

        .perforation-line::before {
            left: -33px;
        }

        .perforation-line::after {
            right: -33px;
        }

        .coupon-details {
            padding: 30px;
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .discount-val {
            font-size: 3rem;
            line-height: 1;
            color: #ff4757;
            font-weight: 900;
            letter-spacing: -1.5px;
        }

        .coupon-code-wrapper {
            border: 2px dashed #6eb356;
            padding: 10px 20px;
            border-radius: 14px;
            background: #f8fff4;
            display: inline-flex;
            align-items: center;
            gap: 15px;
            margin: 20px auto;
        }

        .copyCode {
            font-family: 'Inter', system-ui, sans-serif;
            font-weight: 800;
            color: #2f3542;
            font-size: 1.35rem;
            letter-spacing: 0.5px;
        }

        .copyBtn {
            cursor: pointer;
            padding: 7px 16px;
            background: #6eb356;
            color: white !important;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(110, 179, 86, 0.25);
        }

        .copyBtn:hover {
            background: #5a9645;
            transform: translateY(-2px);
        }

        .timing-countdown li {
            background: #fff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            min-width: 60px;
            padding: 10px 5px !important;
            border: 1px solid #f5f5f5;
            border-radius: 12px !important;
        }

        .off-tag {
            background: #ff7675;
            color: white;
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 800;
            text-transform: uppercase;
        }
    </style>

    <!--campaign section start-->
    <section class="tt-campaigns ptb-100" style="background: #f9fbff;">
        <div class="container">
            <div class="row g-4">

                @php
                    $coupons = \App\Models\Coupon::where('end_date', '>=', strtotime(date('Y-m-d')))
                        ->latest()
                        ->get()
                        ->filter(function ($coupon) {
                            return $coupon->total_usage_count < $coupon->total_usage_limit;
                        });
                @endphp

                @forelse ($coupons as $coupon)
                    <div class="col-lg-4 col-md-6">
                        <div class="tt-coupon-single shadow-sm border-0">
                            <!-- Top Image Section -->
                            <div class="coupon-banner-wrap">
                                <img src="{{ uploadedAsset($coupon->banner) }}" alt="{{ $coupon->code }}">
                            </div>

                            <!-- Perforation effect -->
                            <div class="perforation-line"></div>

                            <!-- Bottom Details Section -->
                            <div class="coupon-details">
                                <div class="offer-text">
                                    <span
                                        class="up-to d-block text-muted text-uppercase fw-bold fs-xs mb-2">{{ localize('UP TO') }}</span>
                                    <div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
                                        <span class="discount-val">
                                            {{ $coupon->discount_type != 'flat' ? (int) $coupon->discount_value : formatPrice($coupon->discount_value) }}@if ($coupon->discount_type != 'flat')<small
                                                    style="font-size: 1.5rem; font-weight: 700;">%</small>@endif
                                        </span>
                                        <span class="off-tag">{{ localize('Off') }}</span>
                                    </div>
                                </div>

                                <div class="coupon-code-container">
                                    <div class="coupon-code-wrapper">
                                        <span class="copyCode">{{ $coupon->code }}</span>
                                        <span class="copyBtn copy-text"
                                            data-clipboard-text="{{ $coupon->code }}">{{ localize('Copy') }}</span>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <ul class="timing-countdown countdown-timer d-flex justify-content-center gap-2 mb-0"
                                        data-date="{{ date('m/d/Y', $coupon->end_date) }} 23:59:59">
                                        <li class="list-inline-item">
                                            <h5 class="mb-0 days fs-sm fw-bold text-dark">00</h5>
                                            <span class="gshop-subtitle fs-xxs d-block">{{ localize('Days') }}</span>
                                        </li>
                                        <li class="list-inline-item">
                                            <h5 class="mb-0 hours fs-sm fw-bold text-dark">00</h5>
                                            <span class="gshop-subtitle fs-xxs d-block">{{ localize('Hrs') }}</span>
                                        </li>
                                        <li class="list-inline-item">
                                            <h5 class="mb-0 minutes fs-sm fw-bold text-dark">00</h5>
                                            <span class="gshop-subtitle fs-xxs d-block">{{ localize('Min') }}</span>
                                        </li>
                                        <li class="list-inline-item">
                                            <h5 class="mb-0 seconds fs-sm fw-bold text-dark">00</h5>
                                            <span class="gshop-subtitle fs-xxs d-block">{{ localize('Sec') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 col-md-6 mx-auto text-center py-5">
                        <img src="{{ staticAsset('frontend/default/assets/img/no-data-found.png') }}" class="img-fluid mb-4"
                            alt="" style="max-height: 250px;">
                        <h4 class="text-muted">{{ localize('No active coupons found at the moment.') }}</h4>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!--campaign section end-->
@endsection
