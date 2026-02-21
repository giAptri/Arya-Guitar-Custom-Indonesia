<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Bulanan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; color: #333; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .total-row td { background-color: #f4f4f4; font-weight: bold; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header">
        <h2>ARYA GUITAR CUSTOM</h2>
        <p>Laporan Penjualan Bulanan</p>
        <p>Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
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
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>
                    {{ $order->customer_name }}<br>
                    <span style="color: #666; font-size: 10px;">{{ $order->customer_phone }}</span>
                </td>
                <td>{{ ucfirst($order->guitar_type) }}</td>
                <td>Rp {{ number_format($order->estimated_price, 0, ',', '.') }}</td>
                <td>{{ ucfirst($order->status) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">Total Pendapatan</td>
                <td colspan="2">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
