<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 11px; }
        .kop-header { font-size: 14pt; font-weight: bold; text-align: left; }
        .kop-sub { font-size: 9pt; color: #555555; }
        .section-header { font-size: 11pt; font-weight: bold; background-color: #0f172a; color: #ffffff; padding: 6px; }
        th { background-color: #1e293b; color: #ffffff; font-weight: bold; border: 1px solid #000000; padding: 6px; text-align: center; }
        td { border: 1px solid #cccccc; padding: 5px; }
        .num { mso-number-format:"\#\,\#\#0"; text-align: right; }
        .curr { mso-number-format:"\"Rp\"\#\,\#\#0"; text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .bg-subtle { background-color: #f1f5f9; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="10" class="kop-header">CTRL+R HARDWARE & COMPUTER STORE</td>
        </tr>
        <tr>
            <td colspan="10" class="kop-sub">REKAPITULASI PENJUALAN & LABA RUGI (GROSS PROFIT)</td>
        </tr>
        <tr>
            <td colspan="10" class="kop-sub">Tanggal Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr><td colspan="10"></td></tr>

        <!-- FINANCIAL SUMMARY -->
        <tr class="bg-subtle fw-bold">
            <td colspan="2">Total Transaksi:</td>
            <td class="num">{{ $sales->count() }} Transaksi</td>
            <td colspan="2">Total Pendapatan (Omzet):</td>
            <td colspan="2" class="curr">{{ $totalRevenue }}</td>
            <td colspan="2">Total Laba Kotor (Profit):</td>
            <td class="curr" style="color: #047857;">{{ $totalGrossProfit }}</td>
        </tr>
        <tr class="bg-subtle fw-bold">
            <td colspan="2">Total HPP (Modal):</td>
            <td class="curr">{{ $totalCost }}</td>
            <td colspan="2">Rata-rata Margin Keuntungan:</td>
            <td colspan="2" class="num">{{ $totalRevenue > 0 ? round(($totalGrossProfit / $totalRevenue) * 100, 1) : 0 }}%</td>
            <td colspan="3"></td>
        </tr>
        <tr><td colspan="10"></td></tr>

        <!-- TRANSACTION TABLE -->
        <tr>
            <td colspan="10" class="section-header">DAFTAR TRANSAKSI PENJUALAN & ANALISIS LABA KOTOR</td>
        </tr>
        <tr>
            <th>No</th>
            <th>No. Invoice</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Tipe Order</th>
            <th>Total Omzet</th>
            <th>Total HPP Modal</th>
            <th>Laba Kotor</th>
            <th>Metode Bayar</th>
            <th>Status Bayar</th>
        </tr>
        @foreach($sales as $index => $s)
            @php
                $saleCost = 0;
                foreach($s->items as $item) {
                    $itemCost = $item->product ? ($item->product->purchase_price > 0 ? $item->product->purchase_price : 0) : 0;
                    $saleCost += ($itemCost * $item->quantity);
                }
                $saleProfit = max(0, (float)$s->total - (float)$saleCost);
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td style="mso-number-format:'\@';" class="fw-bold">{{ $s->sale_number }}</td>
                <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $s->buyer->name ?? $s->user->name ?? 'Pelanggan Umum' }}</td>
                <td class="text-center">{{ $s->buyer_id ? 'Online Store' : 'Kasir Offline' }}</td>
                <td class="curr">{{ $s->total }}</td>
                <td class="curr">{{ $saleCost }}</td>
                <td class="curr fw-bold" style="color: #047857;">{{ $saleProfit }}</td>
                <td class="text-center" style="text-transform: uppercase;">{{ $s->payment_method ?? 'Cash' }}</td>
                <td class="text-center fw-bold">{{ strtoupper($s->payment_status) }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
