<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\Penjualan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $transaksiHariIni = Penjualan::whereDate('penjualan_tanggal', today())->count();
            $omzetHariIni = Penjualan::whereDate('penjualan_tanggal', today())
                ->with('details')->get()
                ->sum(fn($p) => $p->details->sum(fn($d) => $d->harga * $d->jumlah));
            $omzetBulanIni = Penjualan::whereMonth('penjualan_tanggal', now()->month)
                ->with('details')->get()
                ->sum(fn($p) => $p->details->sum(fn($d) => $d->harga * $d->jumlah));

            return [
                Stat::make('Transaksi Hari Ini', $transaksiHariIni)
                    ->icon('heroicon-o-shopping-cart')->color('success'),
                Stat::make('Omzet Hari Ini', 'Rp ' . number_format($omzetHariIni, 0, ',', '.'))
                    ->icon('heroicon-o-banknotes')->color('warning'),
                Stat::make('Omzet Bulan Ini', 'Rp ' . number_format($omzetBulanIni, 0, ',', '.'))
                    ->icon('heroicon-o-chart-bar')->color('info'),
                Stat::make('Total Barang', Barang::count())
                    ->icon('heroicon-o-archive-box')->color('primary'),
            ];
        }

        // Admin (Kasir)
        $transaksiSaya = Penjualan::where('user_id', $user->user_id)
            ->whereDate('penjualan_tanggal', today())->count();
        $totalSaya = Penjualan::where('user_id', $user->user_id)
            ->whereDate('penjualan_tanggal', today())
            ->with('details')->get()
            ->sum(fn($p) => $p->details->sum(fn($d) => $d->harga * $d->jumlah));

        return [
            Stat::make('Transaksi Saya Hari Ini', $transaksiSaya)
                ->icon('heroicon-o-shopping-cart')->color('success'),
            Stat::make('Total Penjualan Saya', 'Rp ' . number_format($totalSaya, 0, ',', '.'))
                ->icon('heroicon-o-banknotes')->color('warning'),
        ];
    }
}
