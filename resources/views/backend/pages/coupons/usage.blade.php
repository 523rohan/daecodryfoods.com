@extends('backend.layouts.master')

@section('title')
    {{ localize('Coupon Usage History') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Usage History for') }}: <span class="text-primary">{{ $coupon->code }}</span></h2>
                            </div>
                            <div class="tt-action">
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary"><i
                                        data-feather="arrow-left"></i> {{ localize('Back to Coupons') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header border-bottom-0">
                            <h5 class="mb-0">{{ localize('Total Usage') }}: {{ $coupon->total_usage_count }} / {{ $coupon->total_usage_limit }}</h5>
                        </div>

                        <table class="table tt-footable border-top" data-use-parent-width="true">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ localize('S/L') }}</th>
                                    <th>{{ localize('Order Code') }}</th>
                                    <th>{{ localize('Customer') }}</th>
                                    <th>{{ localize('Phone') }}</th>
                                    <th data-breakpoints="xs sm">{{ localize('Status') }}</th>
                                    <th data-breakpoints="xs sm">{{ localize('Amount') }}</th>
                                    <th data-breakpoints="xs sm">{{ localize('Date') }}</th>
                                    <th data-breakpoints="xs sm" class="text-end">{{ localize('View Order') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $key => $order)
                                    <tr>
                                        <td class="text-center">
                                            {{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                        <td>
                                            <span class="fw-bold">{{ getSetting('order_code_prefix') }}{{ $order->orderGroup->order_code }}</span>
                                        </td>
                                        <td>
                                            @if($order->user)
                                                {{ $order->user->name }}<br>
                                                <small class="text-muted">{{ $order->user->email }}</small>
                                            @else
                                                <span class="text-muted">{{ localize('Guest') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->user->phone ?? $order->orderGroup->phone ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info text-capitalize">{{ str_replace('_', ' ', $order->delivery_status) }}</span>
                                        </td>
                                        <td>
                                            {{ formatPrice($order->grand_total) }}
                                        </td>
                                        <td>{{ date('d-m-Y H:i', strtotime($order->created_at)) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!--pagination start-->
                        <div class="d-flex align-items-center justify-content-between px-4 pb-4">
                            <span>{{ localize('Showing') }}
                                {{ $orders->firstItem() }}-{{ $orders->lastItem() }} {{ localize('of') }}
                                {{ $orders->total() }} {{ localize('results') }}</span>
                            <nav>
                                {{ $orders->appends(request()->input())->links() }}
                            </nav>
                        </div>
                        <!--pagination end-->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
