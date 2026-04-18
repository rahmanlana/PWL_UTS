<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;

class StrukController extends Controller
{
    public function print($id)
    {
        $penjualan = Penjualan::with(['details.barang', 'user'])
            ->findOrFail($id);

        $total = $penjualan->details->sum(fn($d) => $d->harga * $d->jumlah);

        return view('struk.print', compact('penjualan', 'total'));
    }
}
