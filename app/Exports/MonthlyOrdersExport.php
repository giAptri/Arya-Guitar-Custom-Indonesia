<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MonthlyOrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Order::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Pesanan',
            'Tanggal',
            'Nama Pelanggan',
            'No. Telepon',
            'Tipe Gitar',
            'Estimasi Harga',
            'Status',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_code,
            $order->created_at->format('d-m-Y'),
            $order->customer_name,
            $order->customer_phone,
            ucfirst($order->guitar_type),
            $order->estimated_price,
            ucfirst($order->status),
        ];
    }
}
