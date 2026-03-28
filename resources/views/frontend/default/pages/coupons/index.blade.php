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
            overflow: hidden;
            border-radius: 20px;
            min-height: 320px;
            transition: all 0.3s ease;
            position: relative;
        }

        .tt-coupon-single:hover {
            transform: translateY(-8px);
        }

        .coupon-glass {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 16px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }

        .discount-val {
            font-size: 3.5rem;
            line-height: 1;
            color: #d63384;
            /* Deep pink/red */
            font-weight: 900;
            letter-spacing: -2px;
        }

        .coupon-code-wrapper {
            border: 2px dashed #6eb356;
            padding: 8px 16px;
            border-radius: 12px;
            background: #fafff8;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .copyCode {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-weight: 800;
            color: #2d3436;
            font-size: 1.25rem;
            letter-spacing: 1px;
        }

        .copyBtn {
            cursor: pointer;
            padding: 6px 14px;
            background: #6eb356;
            color: white !important;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(110, 179, 86, 0.3);
        }

        .copyBtn:hover {
            background: #5a9645;
            transform: scale(1.05);
        }

        .timing-countdown li {
            background: #fff !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            min-width: 55px;
            padding: 8px 5px !important;
            border: 1px solid #f1f1f1;
        }

        .off-tag {
            background: #ff7675;
            color: white;
            padding: 2px 10px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-left: 5px;
        }
    </style>

    <!--campaign section start-->
    <section class="tt-campaigns ptb-100 p-0" style="background: #f9fbff;">
        <div class="container">
            <div class="row g-4">

                @php
                    $coupons = \App\Models\Coupon::where('end_date', '>=', strtotime(date('Y-m-d')))
                        ->latest()
                        ->get();
                @endphp

                @forelse ($coupons as $coupon)
                    <div class="col-lg-4 col-md-6">
                        <div class="card shadow-sm border-0 tt-coupon-single p-3"
                            style="background: url('{{ uploadedAsset($coupon->banner) }}') no-repeat center center / cover">
                            <div class="coupon-glass p-4 text-center">
                                <div class="offer-text">
                                    <span
                                        class="up-to d-block text-muted text-uppercase fw-bold fs-xs mb-1">{{ localize('UP TO') }}</span>
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

                                <div class="mt-2">
                                    <ul class="timing-countdown countdown-timer d-flex justify-content-center gap-2 mb-0"
                                        data-date="{{ date('m/d/Y', $coupon->end_date) }} 23:59:59">
                                        <li class="list-inline-item rounded-3">
                                            <h5 class="mb-0 days fs-sm fw-bold">00</h5>
                                            <span class="gshop-subtitle fs-xxs d-block">{{ localize('Days') }}</span>
                                        </li>
                                        <li class="list-inline-item rounded-3">
                                            <h5 class="mb-0 hours fs-sm fw-bold">00</h5>
                                            <span class="gshop-subtitle fs-xxs d-block">{{ localize('Hrs') }}</span>
                                        </li>
                                        <li class="list-inline-item rounded-3">
                                            <h5 class="mb-0 minutes fs-sm fw-bold">00</h5>
                                            <span class="gshop-subtitle fs-xxs d-block">{{ localize('Min') }}</span>
                                        </li>
                                        <li class="list-inline-item rounded-3">
                                            <h5 class="mb-0 seconds fs-sm fw-bold">00</h5>
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
