<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokResource\Pages;
use App\Models\Barang;
use App\Models\Stok;
use App\Models\Supplier;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class StokResource extends Resource
{
    protected static ?string $model = Stok::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('supplier_id')
                ->label('Supplier')
                ->options(Supplier::pluck('supplier_nama', 'supplier_id'))
                ->required(),
            Select::make('barang_id')
                ->label('Barang')
                ->options(Barang::pluck('barang_nama', 'barang_id'))
                ->required(),
            Hidden::make('user_id')
                ->default(fn() => Auth::id()),
            DateTimePicker::make('stok_tanggal')
                ->required()
                ->default(now()),
            TextInput::make('stok_jumlah')
                ->numeric()
                ->required()
                ->minValue(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('stok_id')->label('ID')->sortable(),
            TextColumn::make('barang.barang_nama')->label('Barang'),
            TextColumn::make('supplier.supplier_nama')->label('Supplier'),
            TextColumn::make('user.nama')->label('Input oleh'),
            TextColumn::make('stok_jumlah')->label('Jumlah'),
            TextColumn::make('stok_tanggal')->dateTime()->label('Tanggal'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStoks::route('/'),
            'create' => Pages\CreateStok::route('/create'),
            'edit'   => Pages\EditStok::route('/{record}/edit'),
        ];
    }
}
