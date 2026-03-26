<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $customers = User::where('user_type', 'customer')->latest();

        if ($this->request->search != null) {
            $customers = $customers->where(function ($query) {
                $query->where('name', 'like', '%' . $this->request->search . '%')
                    ->orWhere('email', 'like', '%' . $this->request->search . '%');
            });
        }

        if ($this->request->is_banned != null) {
            $customers = $customers->where('is_banned', $this->request->is_banned);
        }

        return $customers->get();
    }

    public function headings(): array
    {
        return [
            "Name",
            "Email",
            "Phone",
            "Banned Status"
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->name,
            $customer->email,
            $customer->phone,
            $customer->is_banned == 1 ? 'Banned' : 'Active'
        ];
    }
}
