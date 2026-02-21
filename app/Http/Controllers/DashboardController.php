<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Guitar;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

use App\Exports\MonthlyOrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new MonthlyOrdersExport, 'laporan-penjualan-' . now()->format('Y-m') . '.xlsx');
    }

    public function exportPdf()
    {
        $orders = Order::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->get();

        $totalIncome = $orders->sum('estimated_price');

        $pdf = Pdf::loadView('exports.monthly_orders_pdf', [
            'orders' => $orders,
            'totalIncome' => $totalIncome
        ]);

        return $pdf->download('laporan-penjualan-' . now()->format('Y-m') . '.pdf');
    }

    public function index()
    {
        // 1. Total Produk (Active Guitars)
        $totalProducts = Guitar::where('is_active', true)->count();

        // 2. Pesanan Bulan Ini
        $ordersThisMonth = Order::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // 3. Jumlah Pelanggan (Registered & Ordered)
        $totalCustomers = Customer::has('orders')->count();

        // 4. Grafik Penjualan (Income vs Expenses)
        // For now, Expenses = 0. Income = Sum of estimated_price from Orders.
        // We will fetch data for the current year, grouped by month.

        // 4. Grafik Penjualan (Income vs Expenses)
        // Using Collection method to be database-agnostic (works with MySQL, SQLite, PostgreSQL)
        $salesData = Order::whereYear('created_at', now()->year)
            ->get()
            ->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('n'); // 1-12
            })
            ->map(function ($row) {
                return $row->sum('estimated_price');
            })
            ->toArray();

        // Prepare chart data for all 12 months
        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des'
        ];

        $chartData = [];
        $maxIncome = 0;

        foreach ($months as $num => $label) {
            $income = $salesData[$num] ?? 0;
            if ($income > $maxIncome) {
                $maxIncome = $income;
            }

            $chartData[] = [
                'label' => $label,
                'income' => $income,
                'expense' => 0, // Placeholder
            ];
        }

        // Calculate height percentages for the chart
        // Avoid division by zero
        $maxScale = $maxIncome > 0 ? $maxIncome : 100; // Default scale if no data

        foreach ($chartData as &$data) {
            $data['height'] = round(($data['income'] / $maxScale) * 100) . '%';
            $data['formatted_income'] = 'Rp ' . number_format($data['income'], 0, ',', '.');
        }

        return Inertia::render('Dashboard', [
            'stats' => [
                [
                    'title' => 'Total Produk',
                    'value' => $totalProducts
                ],
                [
                    'title' => 'Pesanan Bulan Ini',
                    'value' => $ordersThisMonth
                ],
                [
                    'title' => 'Jumlah Pelanggan',
                    'value' => $totalCustomers
                ],
            ],
            'salesChart' => $chartData,
        ]);
    }
}
