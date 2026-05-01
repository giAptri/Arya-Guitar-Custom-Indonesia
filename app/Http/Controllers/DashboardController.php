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
    /**
     * Export Excel — mendukung parameter ?year=&month=
     */
    public function exportExcel(Request $request)
    {
        $year  = (int) $request->query('year',  now()->year);
        $month = (int) $request->query('month', now()->month);

        $filename = 'laporan-penjualan-' . sprintf('%04d-%02d', $year, $month) . '.xlsx';

        return Excel::download(new MonthlyOrdersExport($year, $month), $filename);
    }

    /**
     * Export PDF — mendukung parameter ?year=&month=
     */
    public function exportPdf(Request $request)
    {
        $year  = (int) $request->query('year',  now()->year);
        $month = (int) $request->query('month', now()->month);

        $orders = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $totalIncome = $orders->sum('estimated_price');

        $periodLabel = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

        $pdf = Pdf::loadView('exports.monthly_orders_pdf', [
            'orders'      => $orders,
            'totalIncome' => $totalIncome,
            'periodLabel' => $periodLabel,
        ]);

        $filename = 'laporan-penjualan-' . sprintf('%04d-%02d', $year, $month) . '.pdf';

        return $pdf->download($filename);
    }

    public function index(Request $request)
    {
        // Ambil periode dari query string, default ke bulan & tahun sekarang
        $year  = (int) $request->query('year',  now()->year);
        $month = (int) $request->query('month', now()->month);

        // 1. Total Produk (Active Guitars) — tidak bergantung periode
        $totalProducts = Guitar::where('is_active', true)->count();

        // 2. Pesanan Periode yang Dipilih
        $ordersThisPeriod = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        // 3. Jumlah Pelanggan yang pernah pesan
        $totalCustomers = Customer::has('orders')->count();

        // 4. Grafik Penjualan per bulan untuk tahun yang dipilih
        $salesData = Order::whereYear('created_at', $year)
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->created_at)->format('n'); // 1-12
            })
            ->map(function ($row) {
                return $row->sum('estimated_price');
            })
            ->toArray();

        $months = [
            1  => 'Jan',
            2  => 'Feb',
            3  => 'Mar',
            4  => 'Apr',
            5  => 'Mei',
            6  => 'Jun',
            7  => 'Jul',
            8  => 'Agu',
            9  => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];

        $chartData = [];
        $maxIncome = 0;

        foreach ($months as $num => $label) {
            $income = $salesData[$num] ?? 0;
            if ($income > $maxIncome) {
                $maxIncome = $income;
            }

            $chartData[] = [
                'label'   => $label,
                'income'  => $income,
                'expense' => 0,
            ];
        }

        $maxScale = $maxIncome > 0 ? $maxIncome : 100;

        foreach ($chartData as &$data) {
            $data['height']           = round(($data['income'] / $maxScale) * 100) . '%';
            $data['formatted_income'] = 'Rp ' . number_format($data['income'], 0, ',', '.');
        }

        $periodLabel = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

        return Inertia::render('Dashboard', [
            'stats' => [
                [
                    'title' => 'Total Produk',
                    'value' => $totalProducts,
                ],
                [
                    'title' => 'Pesanan ' . $periodLabel,
                    'value' => $ordersThisPeriod,
                ],
                [
                    'title' => 'Jumlah Pelanggan',
                    'value' => $totalCustomers,
                ],
            ],
            'salesChart'    => $chartData,
            'selectedYear'  => $year,
            'selectedMonth' => $month,
        ]);
    }
}
