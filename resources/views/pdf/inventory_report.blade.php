<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris & Mutasi Stok - Ctrl+R</title>
    <style>
        @page {
            margin: 24px 30px;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 10px;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-table {
            border-bottom: 3px double #0f172a;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .company-tagline {
            font-size: 9.5px;
            color: #475569;
            margin-top: 1px;
        }
        .company-contact {
            font-size: 8.5px;
            color: #64748b;
            margin-top: 2px;
        }
        .report-title-block {
            text-align: right;
        }
        .report-badge {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-meta {
            font-size: 8.5px;
            color: #475569;
            margin-top: 2px;
        }

        .kpi-table {
            margin-bottom: 14px;
        }
        .kpi-card {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 8px;
            text-align: center;
        }
        .kpi-title {
            font-size: 8px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
        }
        .kpi-value {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin-top: 12px;
            margin-bottom: 6px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 2px;
        }

        .data-table {
            margin-bottom: 12px;
            border: 1px solid #cbd5e1;
        }
        .data-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 8.5px;
            text-transform: uppercase;
            font-weight: bold;
            padding: 6px 5px;
            border: 1px solid #0f172a;
        }
        .data-table td {
            padding: 5px;
            border: 1px solid #e2e8f0;
            font-size: 9px;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }

        .badge-status {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-safe { background-color: #dcfce7; color: #166534; }
        .status-low { background-color: #fef9c3; color: #854d0e; }
        .status-out { background-color: #fee2e2; color: #991b1b; }

        .signatures-table {
            margin-top: 22px;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 33%;
            vertical-align: top;
            font-size: 9px;
        }
        .signature-line {
            margin-top: 45px;
            border-top: 1px solid #475569;
            width: 75%;
            margin-left: auto;
            margin-right: auto;
            padding-top: 3px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <table class="kop-table">
        <tr>
            <td style="width: 55px;">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo" style="width: 48px; height: 48px; object-fit: contain;">
                @endif
            </td>
            <td>
                <div class="company-name">CTRL+R HARDWARE & COMPUTER STORE</div>
                <div class="company-tagline">Sistem Manajemen Logistik & Inventaris Terintegrasi</div>
                <div class="company-contact">Jl. Boulevard Teknologi No. 42, Jakarta Selatan | Telp: (021) 555-8901 | Email: support@ctrl-r.store</div>
            </td>
            <td class="report-title-block">
                <div class="report-badge">LAPORAN MUTASI & INVENTARIS</div>
                <div class="report-meta"><strong>Dicetak:</strong> {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
                <div class="report-meta"><strong>Kategori:</strong> {{ $selectedCategoryName ?? 'Semua Kategori' }}</div>
            </td>
        </tr>
    </table>

    <!-- RINGKASAN EKSEKUTIF / KPI -->
    <table class="kpi-table">
        <tr>
            <td style="width: 25%; padding-right: 4px;">
                <div class="kpi-card">
                    <div class="kpi-title">Total Produk</div>
                    <div class="kpi-value font-mono">{{ number_format($totalProducts) }} Item</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 2px;">
                <div class="kpi-card">
                    <div class="kpi-title">Nilai Aset Stok</div>
                    <div class="kpi-value font-mono" style="color: #047857;">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 2px;">
                <div class="kpi-card">
                    <div class="kpi-title">Akumulasi Masuk</div>
                    <div class="kpi-value font-mono" style="color: #0284c7;">+{{ number_format($grandTotalIn) }} Unit</div>
                </div>
            </td>
            <td style="width: 25%; padding-left: 4px;">
                <div class="kpi-card">
                    <div class="kpi-title">Akumulasi Keluar</div>
                    <div class="kpi-value font-mono" style="color: #dc2626;">-{{ number_format($grandTotalOut) }} Unit</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- BAGIAN 1: RINGKASAN PER KATEGORI -->
    <div class="section-title">1. Ringkasan Nilai & Stok Berdasarkan Kategori</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 35%;">Nama Kategori</th>
                <th style="width: 15%;" class="text-center">Jumlah Item</th>
                <th style="width: 15%;" class="text-center">Total Stok Fisik</th>
                <th style="width: 15%;" class="text-right">Rata-rata Harga</th>
                <th style="width: 15%;" class="text-right">Total Nilai Aset</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryReports as $index => $cat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $cat->name }}</strong></td>
                    <td class="text-center font-mono">{{ $cat->total_products }} item</td>
                    <td class="text-center font-mono">{{ number_format($cat->total_stock) }} unit</td>
                    <td class="text-right font-mono">Rp {{ number_format($cat->average_price, 0, ',', '.') }}</td>
                    <td class="text-right font-mono">Rp {{ number_format($cat->category_inventory_value, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- BAGIAN 2: RINCIAN MUTASI PER PRODUK -->
    <div class="section-title">2. Rekapitulasi Mutasi Stok per Item Produk</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 14%;">SKU</th>
                <th style="width: 30%;">Nama Produk</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 13%;" class="text-right">Harga Jual</th>
                <th style="width: 8%;" class="text-center">Masuk</th>
                <th style="width: 8%;" class="text-center">Keluar</th>
                <th style="width: 8%;" class="text-center">Sisa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productReports as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $p->sku }}</td>
                    <td><strong>{{ $p->name }}</strong></td>
                    <td>{{ $p->category->name ?? '-' }}</td>
                    <td class="text-right font-mono">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    <td class="text-center font-mono" style="color: #0284c7;">+{{ $p->total_in }}</td>
                    <td class="text-center font-mono" style="color: #dc2626;">-{{ $p->total_out }}</td>
                    <td class="text-center font-mono">
                        <strong>{{ $p->current_stock }}</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TANDA TANGAN PENGESAHAN -->
    <table class="signatures-table">
        <tr>
            <td class="signature-box">
                <div>Dibuat Oleh (Admin Logistik),</div>
                <div class="signature-line">
                    {{ Auth::user()->name ?? 'Staf Logistik' }}
                </div>
            </td>
            <td class="signature-box">
                <div>Diperiksa Oleh (Kepala Gudang),</div>
                <div class="signature-line">
                    Manager Gudang
                </div>
            </td>
            <td class="signature-box">
                <div>Disetujui Oleh (Direktur),</div>
                <div class="signature-line">
                    Pimpinan Perusahaan
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
