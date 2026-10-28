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
            <td colspan="7" class="kop-header">CTRL+R HARDWARE & COMPUTER STORE</td>
        </tr>
        <tr>
            <td colspan="7" class="kop-sub">LAPORAN MUTASI & REKAPITULASI INVENTARIS BARANG</td>
        </tr>
        <tr>
            <td colspan="7" class="kop-sub">Tanggal Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB | Kategori: {{ $selectedCategoryName ?? 'Semua Kategori' }}</td>
        </tr>
        <tr><td colspan="7"></td></tr>

        <!-- KPI SUMMARY -->
        <tr class="bg-subtle fw-bold">
            <td colspan="2">Total Macam Produk:</td>
            <td class="num">{{ $totalProducts }} Item</td>
            <td colspan="2">Total Nilai Inventaris:</td>
            <td colspan="2" class="curr">{{ $totalInventoryValue }}</td>
        </tr>
        <tr class="bg-subtle fw-bold">
            <td colspan="2">Total Akumulasi Barang Masuk:</td>
            <td class="num">+{{ $grandTotalIn }} Unit</td>
            <td colspan="2">Total Akumulasi Barang Keluar:</td>
            <td colspan="2" class="num">-{{ $grandTotalOut }} Unit</td>
        </tr>
        <tr><td colspan="7"></td></tr>

        <!-- CATEGORY RECAP -->
        <tr>
            <td colspan="7" class="section-header">1. RINGKASAN STOK PER KATEGORI</td>
        </tr>
        <tr>
            <th>No</th>
            <th colspan="2">Nama Kategori</th>
            <th>Total Macam Item</th>
            <th>Total Stok Fisik</th>
            <th>Rata-rata Harga</th>
            <th>Total Nilai Aset</th>
        </tr>
        @foreach($categoryReports as $index => $cat)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td colspan="2" class="fw-bold">{{ $cat->name }}</td>
            <td class="num">{{ $cat->total_products }} item</td>
            <td class="num">{{ $cat->total_stock }} unit</td>
            <td class="curr">{{ $cat->average_price }}</td>
            <td class="curr">{{ $cat->category_inventory_value }}</td>
        </tr>
        @endforeach
        <tr><td colspan="7"></td></tr>

        <!-- DETAILED PRODUCT MUTATION -->
        <tr>
            <td colspan="7" class="section-header">2. RINCIAN MUTASI & SISA STOK PER PRODUK</td>
        </tr>
        <tr>
            <th>No</th>
            <th>SKU</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga Jual Satuan</th>
            <th>Total Masuk</th>
            <th>Total Keluar</th>
            <th>Sisa Stok Fisik</th>
            <th>Status Stok</th>
        </tr>
        @foreach($productReports as $index => $p)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td style="mso-number-format:'\@';">{{ $p->sku }}</td>
            <td>{{ $p->name }}</td>
            <td>{{ $p->category->name ?? '-' }}</td>
            <td class="curr">{{ $p->price }}</td>
            <td class="num" style="color: #0284c7;">+{{ $p->total_in }}</td>
            <td class="num" style="color: #dc2626;">-{{ $p->total_out }}</td>
            <td class="num fw-bold">{{ $p->current_stock }}</td>
            <td class="text-center">
                @if($p->current_stock <= 0) HABIS
                @elseif($p->current_stock <= $p->min_stock) KRITIS
                @else AMAN
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>
