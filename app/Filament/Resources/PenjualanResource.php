<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Barang;
use App\Models\Penjualan;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('penjualan_kode')
                ->default(fn() => 'TRX-' . date('YmdHis'))
                ->required()
                ->maxLength(20),
            TextInput::make('pembeli')
                ->maxLength(50)
                ->nullable(),
            DateTimePicker::make('penjualan_tanggal')
                ->required()
                ->default(now()),
            Hidden::make('user_id')
                ->default(fn() => Auth::id()),

            Repeater::make('details')
                ->relationship()
                ->schema([
                    Select::make('barang_id')
                        ->label('Barang')
                        ->options(Barang::pluck('barang_nama', 'barang_id'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, Set $set) {
                            $barang = Barang::find($state);
                            if ($barang) {
                                $set('harga', $barang->harga_jual);
                            }
                        }),
                    TextInput::make('harga')
                        ->numeric()
                        ->required()
                        ->prefix('Rp'),
                    TextInput::make('jumlah')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->default(1),
                ])
                ->columns(3)
                ->label('Detail Barang'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('penjualan_kode')->searchable(),
            TextColumn::make('pembeli'),
            TextColumn::make('user.nama')->label('Kasir'),
            TextColumn::make('penjualan_tanggal')->dateTime(),
            TextColumn::make('details_count')
                ->counts('details')
                ->label('Jml Item'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit'   => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
