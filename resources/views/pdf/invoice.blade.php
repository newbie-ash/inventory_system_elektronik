<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $sale->sale_number }} - Ctrl+R</title>
    <style>
        @page {
            margin: 24px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-table {
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .company-tagline {
            font-size: 10px;
            color: #475569;
            margin-top: 2px;
        }
        .company-contact {
            font-size: 9px;
            color: #64748b;
            margin-top: 3px;
        }
        .invoice-title-block {
            text-align: right;
        }
        .invoice-badge {
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .invoice-meta {
            font-size: 9.5px;
            color: #334155;
            margin-top: 4px;
        }
        .meta-table {
            margin-bottom: 16px;
        }
        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px;
            vertical-align: top;
        }
        .meta-heading {
            font-size: 9.5px;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .data-table {
            margin-bottom: 16px;
            border: 1px solid #cbd5e1;
        }
        .data-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9.5px;
            text-transform: uppercase;
            font-weight: bold;
            padding: 8px 6px;
            border: 1px solid #0f172a;
        }
        .data-table td {
            padding: 8px 6px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }
        .status-paid { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .status-pending { background-color: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
        .status-failed { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .summary-table {
            width: 50%;
            margin-left: auto;
            margin-bottom: 20px;
        }
        .summary-table td {
            padding: 4px 8px;
            font-size: 10px;
        }
        .summary-total {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            border-top: 2px solid #0f172a;
            background-color: #f1f5f9;
        }

        .signatures-table {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 33%;
            vertical-align: top;
            font-size: 9.5px;
        }
        .signature-line {
            margin-top: 55px;
            border-top: 1px solid #475569;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            padding-top: 4px;
            font-weight: bold;
        }
        .footer-note {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px dashed #cbd5e1;
            font-size: 8.5px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT RESMI -->
    <table class="kop-table">
        <tr>
            <td style="width: 65px;">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo" style="width: 55px; height: 55px; object-fit: contain;">
                @endif
            </td>
            <td>
                <div class="company-name">CTRL+R HARDWARE & COMPUTER STORE</div>
                <div class="company-tagline">Pusat Komponen, Laptop High-Performance, Aksesoris & Servis Resmi</div>
                <div class="company-contact">Jl. Boulevard Teknologi No. 42, Jakarta Selatan | Telp: (021) 555-8901 | Email: billing@ctrl-r.store</div>
            </td>
            <td class="invoice-title-block">
                <div class="invoice-badge">FAKTUR PENJUALAN</div>
                <div class="invoice-meta"><strong>No:</strong> <span class="font-mono">{{ $sale->sale_number }}</span></div>
                <div class="invoice-meta"><strong>Tanggal:</strong> {{ $sale->created_at->format('d/m/Y H:i') }} WIB</div>
            </td>
        </tr>
    </table>

    <!-- INFORMASI TRANSAKSI & PELANGGAN -->
    <table class="meta-table">
        <tr>
            <td class="meta-box" style="width: 48%; margin-right: 4%;">
                <div class="meta-heading">Ditagihkan Kepada (Pelanggan)</div>
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 80px; color: #64748b;">Nama:</td>
                        <td><strong>{{ $sale->buyer->name ?? $sale->user->name ?? 'Pelanggan Umum' }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Email:</td>
                        <td>{{ $sale->buyer->email ?? $sale->user->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">No. Telepon:</td>
                        <td>{{ $sale->buyer->profile->phone_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Tipe Order:</td>
                        <td>{{ $sale->buyer_id ? 'Pemesanan Online (Web Store)' : 'Kasir Offline (Direct POS)' }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 4%;"></td>
            <td class="meta-box" style="width: 48%;">
                <div class="meta-heading">Status & Informasi Pembayaran</div>
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 100px; color: #64748b;">Status Bayar:</td>
                        <td>
                            @if($sale->payment_status === 'paid')
                                <span class="status-badge status-paid">LUNAS (PAID)</span>
                            @elseif($sale->payment_status === 'pending')
                                <span class="status-badge status-pending">MENUNGGU PEMBAYARAN</span>
                            @else
                                <span class="status-badge status-failed">{{ strtoupper($sale->payment_status) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Metode:</td>
                        <td><strong style="text-transform: uppercase;">{{ $sale->payment_method ?? 'Midtrans Payment' }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Status Kirim:</td>
                        <td><strong style="text-transform: uppercase;">{{ $sale->shipping_status ?? 'Diproses' }}</strong></td>
                    </tr>
                    @if($sale->shipping_courier)
                    <tr>
                        <td style="color: #64748b;">Kurir & Tujuan:</td>
                        <td><strong>{{ $sale->shipping_courier }}</strong> {{ $sale->shipping_destination ? '(' . ucwords($sale->shipping_destination) . ')' : '' }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="color: #64748b;">Petugas / Kasir:</td>
                        <td>{{ $sale->user->name ?? 'Sistem Otomatis' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- TABEL ITEM PRODUK -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 18%;">SKU</th>
                <th style="width: 42%;">Nama Produk & Deskripsi</th>
                <th style="width: 15%;" class="text-right">Harga Satuan</th>
                <th style="width: 7%;" class="text-center">Qty</th>
                <th style="width: 13%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $item->product->sku ?? '-' }}</td>
                    <td>
                        <strong>{{ $item->product->name ?? 'Produk Tidak Ditemukan' }}</strong>
                    </td>
                    <td class="text-right font-mono">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-center font-mono">{{ $item->quantity }}</td>
                    <td class="text-right font-mono">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- RINGKASAN PEMBAYARAN -->
    <table class="summary-table">
        <tr>
            <td style="color: #64748b;">Subtotal Item:</td>
            <td class="text-right font-mono">Rp {{ number_format($sale->subtotal ?? $sale->total, 0, ',', '.') }}</td>
        </tr>
        @if(($sale->discount_amount ?? 0) > 0)
            <tr>
                <td style="color: #dc2626;">Potongan Promo ({{ $sale->promo_code ?? 'DISCOUNT' }}):</td>
                <td class="text-right font-mono" style="color: #dc2626;">- Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td style="color: #64748b;">Ongkos Kirim {{ $sale->shipping_courier ? '(' . $sale->shipping_courier . ')' : '' }}:</td>
            <td class="text-right font-mono" style="{{ ($sale->shipping_cost ?? 0) > 0 ? '' : 'color: #16a34a;' }}">
                {{ ($sale->shipping_cost ?? 0) > 0 ? 'Rp ' . number_format($sale->shipping_cost, 0, ',', '.') : 'Gratis' }}
            </td>
        </tr>
        <tr class="summary-total">
            <td>TOTAL TAGIHAN:</td>
            <td class="text-right font-mono" style="font-size: 13px;">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- LEMBAR PENGESAHAN / TANDA TANGAN -->
    <table class="signatures-table">
        <tr>
            <td class="signature-box">
                <div>Penerima / Pembeli,</div>
                <div class="signature-line">
                    {{ $sale->buyer->name ?? $sale->user->name ?? 'Pelanggan' }}
                </div>
            </td>
            <td class="signature-box">
                <div>Admin Gudang & Ekspedisi,</div>
                <div class="signature-line">
                    Bagian Logistik
                </div>
            </td>
            <td class="signature-box">
                <div>Kasir / Finance Manager,</div>
                <div class="signature-line">
                    Ctrl+R Store Official
                </div>
            </td>
        </tr>
    </table>

    <!-- FOOTER / CATATAN RESMI -->
    <div class="footer-note">
        * Faktur ini sah dan diterbitkan secara komputerisasi oleh Ctrl+R Inventory Management System.<br>
        * Garansi resmi berlaku sesuai kartu garansi manufaktur masing-masing produk terhitung sejak tanggal faktur ini diterbitkan.<br>
        * Terima kasih telah berbelanja di Ctrl+R Hardware Store.
    </div>
</body>
</html>
