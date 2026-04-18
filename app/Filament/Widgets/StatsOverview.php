<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPenjualanHariIni = Penjualan::whereDate('penjualan_tanggal', today())->count();
        $totalBarang = Barang::count();
        $totalUser = User::count();

        return [
            Stat::make('Transaksi Hari Ini', $totalPenjualanHariIni)
                ->icon('heroicon-o-shopping-cart')
                ->color('success'),
            Stat::make('Total Barang', $totalBarang)
                ->icon('heroicon-o-archive-box')
                ->color('warning'),
            Stat::make('Total User', $totalUser)
                ->icon('heroicon-o-users')
                ->color('info'),
        ];
    }
}
