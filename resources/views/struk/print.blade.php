<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $penjualan->penjualan_kode }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 300px;
            margin: 0 auto;
            padding: 10px;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; margin: 2px 0; }
        .total-row { display: flex; justify-content: space-between; font-weight: bold; font-size: 13px; }
        @media print {
            body { width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="center bold" style="font-size:14px;">TOKO PWL UTS</div>
    <div class="center">Jl. Contoh No. 1</div>
    <div class="center">Telp: 0812-xxxx-xxxx</div>
    <div class="divider"></div>

    <div class="row">
        <span>Kode</span>
        <span>{{ $penjualan->penjualan_kode }}</span>
    </div>
    <div class="row">
        <span>Tanggal</span>
        <span>{{ \Carbon\Carbon::parse($penjualan->penjualan_tanggal)->format('d/m/Y H:i') }}</span>
    </div>
    <div class="row">
        <span>Kasir</span>
        <span>{{ $penjualan->user->nama }}</span>
    </div>
    <div class="row">
        <span>Pembeli</span>
        <span>{{ $penjualan->pembeli ?? '-' }}</span>
    </div>

    <div class="divider"></div>

    @foreach($penjualan->details as $detail)
    <div style="margin: 4px 0;">
        <div>{{ $detail->barang->barang_nama }}</div>
        <div class="row">
            <span>{{ $detail->jumlah }} x Rp {{ number_format($detail->harga, 0, ',', '.') }}</span>
            <span>Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <div class="divider"></div>
    <div class="total-row">
        <span>TOTAL</span>
        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
    </div>
    <div class="divider"></div>

    <div class="center" style="margin-top: 8px;">Terima Kasih!</div>
    <div class="center">Selamat Berbelanja Kembali</div>

    <br>
    <div class="center no-print">
        <button onclick="window.print()"
            style="padding:8px 20px; cursor:pointer; font-size:13px;">
            🖨️ Print Struk
        </button>
    </div>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>