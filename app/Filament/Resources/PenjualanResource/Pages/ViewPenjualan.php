<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewPenjualan extends ViewRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        if (!auth()->user()->isAdmin()) {
            return [];
        }

        return [
            Action::make('cetak_struk')
                ->label('🖨️ Cetak Struk')
                ->color('success')
                ->url(fn() => route('struk.print', $this->record->penjualan_id))
                ->openUrlInNewTab(),
        ];
    }
}
