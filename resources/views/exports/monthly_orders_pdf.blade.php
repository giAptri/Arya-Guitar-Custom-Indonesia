<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan - {{ $periodLabel }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }

        .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #f5a623; padding-bottom: 12px; }
        .header h2 { margin: 0; font-size: 18px; color: #111; letter-spacing: 2px; }
        .header .subtitle { margin: 4px 0 0; font-size: 12px; color: #555; }
        .header .periode { margin: 6px 0 0; font-size: 13px; font-weight: bold; color: #f5a623; }

        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #111; color: #f5a623; font-weight: bold; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        tr:nth-child(even) { background-color: #fafafa; }
        .total-row td { background-color: #f5f5f5; font-weight: bold; color: #111; }

        .footer { margin-top: 24px; font-size: 10px; color: #aaa; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>ARYA GUITAR CUSTOM</h2>
        <p class="subtitle">Laporan Penjualan Bulanan</p>
        <p class="periode">Periode: {{ $periodLabel }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Kode Pesanan</th>
                <th style="width: 10%">Tanggal</th>
                <th style="width: 20%">Pelanggan</th>
                <th style="width: 15%">Tipe Gitar</th>
                <th style="width: 20%">Estimasi Harga</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>
                    {{ $order->customer_name }}<br>
                    <span style="color: #888; font-size: 10px;">{{ $order->customer_phone }}</span>
                </td>
                <td>{{ ucfirst($order->guitar_type) }}</td>
                <td>Rp {{ number_format($order->estimated_price, 0, ',', '.') }}</td>
                <td>{{ ucfirst($order->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#aaa; padding: 24px;">
                    Tidak ada data pesanan pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">Total Pendapatan</td>
                <td colspan="2">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
