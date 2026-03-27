<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Str;
use DB;

class ProductSalesReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $productsQuery = Product::shop();
        $order = $this->request->order == "ASC" ? "ASC" : "DESC";

        if (Str::contains($this->request->date_range, 'to') && $this->request->date_range != null) {
            $date_var = explode(" to ", $this->request->date_range);
            $startDate = date("Y-m-d", strtotime($date_var[0]));
            $endDate = date("Y-m-d", strtotime($date_var[1]) + 86400);

            $productsQuery = $productsQuery->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
                ->leftJoin('order_items', 'product_variations.id', '=', 'order_items.product_variation_id')
                ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.payment_status', paidPaymentStatus())
                ->where('orders.delivery_status', '!=', orderCancelledStatus())
                ->where('order_items.created_at', '>=', $startDate)
                ->where('order_items.created_at', '<=', $endDate)
                ->select('products.*', DB::raw('SUM(order_items.qty) as period_sale_count'))
                ->groupBy('products.id')
                ->orderBy('period_sale_count', $order);
        } else {
            $productsQuery = $productsQuery->orderBy('total_sale_count', $order)
                ->select('products.*', DB::raw('total_sale_count as period_sale_count'));
        }

        if ($this->request->search != null) {
            $productsQuery = $productsQuery->where('products.name', 'like', '%' . $this->request->search . '%');
        }

        return $productsQuery->get();
    }

    public function headings(): array
    {
        return [
            "Product Name",
            "Total Sales"
        ];
    }

    public function map($product): array
    {
        return [
            $product->collectLocalization('name'),
            $product->period_sale_count ?? 0
        ];
    }
}
