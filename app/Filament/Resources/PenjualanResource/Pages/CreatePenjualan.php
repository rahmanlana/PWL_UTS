<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
