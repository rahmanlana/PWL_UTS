<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Models\Barang;
use App\Models\Kategori;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('kategori_id')
                ->label('Kategori')
                ->options(Kategori::pluck('kategori_nama', 'kategori_id'))
                ->required(),
            TextInput::make('barang_kode')
                ->required()
                ->maxLength(10),
            TextInput::make('barang_nama')
                ->required()
                ->maxLength(100),
            TextInput::make('harga_beli')
                ->numeric()
                ->required()
                ->prefix('Rp'),
            TextInput::make('harga_jual')
                ->numeric()
                ->required()
                ->prefix('Rp'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('barang_kode')->searchable(),
            TextColumn::make('barang_nama')->searchable(),
            TextColumn::make('kategori.kategori_nama')->label('Kategori'),
            TextColumn::make('harga_beli')->money('IDR'),
            TextColumn::make('harga_jual')->money('IDR'),
            TextColumn::make('stok_tersedia')->label('Stok'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit'   => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
