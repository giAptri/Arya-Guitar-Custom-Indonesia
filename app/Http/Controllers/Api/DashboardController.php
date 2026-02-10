<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Customer;
use App\Models\Guitar;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * GET /api/dashboard
     */
    public function index(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        $totalProduk = Guitar::count();
        $pesananBulanIni = Order::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $jumlahPelanggan = Customer::count();

        // Grafik Jan–Des (jumlah order selesai)
        // Gunakan Collection grouping agar database-agnostic
        $monthlyOrders = Order::where('status', 'selesai')
            ->whereYear('created_at', $year)
            ->get()
            ->groupBy(function ($d) {
                return Carbon::parse($d->created_at)->format('n'); // 1-12
            });

        $labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $series = [];
        
        for ($m = 1; $m <= 12; $m++) {
            $series[] = isset($monthlyOrders[$m]) ? $monthlyOrders[$m]->count() : 0;
        }

        // Pelanggan terbaru
        $pelangganTerbaru = Order::with('customer')
            ->latest('created_at')
            ->limit(8)
            ->get();

        return response()->json([
            'year' => $year,
            'cards' => [
                'total_produk' => $totalProduk,
                'pesanan_bulan_ini' => $pesananBulanIni,
                'jumlah_pelanggan' => $jumlahPelanggan,
            ],
            'chart' => [
                'labels' => $labels,
                'series' => $series,
            ],
            // Gunakan Resource agar format konsisten
            'pelanggan_terbaru' => OrderResource::collection($pelangganTerbaru),
        ]);
    }
}
