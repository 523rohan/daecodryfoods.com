<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Str;

class OrdersReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $orders = Order::latest();

        if ($this->request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $this->request->delivery_status);
        }

        if ($this->request->payment_status != null) {
            $orders = $orders->where('payment_status', $this->request->payment_status);
        }

        if (Str::contains($this->request->date_range, 'to') && $this->request->date_range != null) {
            $date_var = explode(" to ", $this->request->date_range);
            $orders = $orders->where('created_at', '>=', date("Y-m-d", strtotime($date_var[0])))
                             ->where('created_at', '<=', date("Y-m-d", strtotime($date_var[1]) + 86400));
        }

        return $orders->get();
    }

    public function headings(): array
    {
        return [
            "Order Code",
            "Customer Name",
            "Placed On",
            "Items Qty",
            "Payment Status",
            "Delivery Status",
            "Amount"
        ];
    }

    public function map($order): array
    {
        return [
            $order->orderGroup->order_code,
            optional($order->user)->name,
            date('d M, Y', strtotime($order->created_at)),
            $order->orderItems()->count(),
            $order->payment_status,
            Str::title(Str::replace('_', ' ', $order->delivery_status)),
            $order->orderGroup->grand_total_amount
        ];
    }
}
