<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Barang;
use App\Models\Penjualan;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ViewAction;
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
            Section::make('Informasi Transaksi')
                ->schema([
                    TextInput::make('penjualan_kode')
                        ->label('Kode Transaksi')
                        ->default(fn() => 'TRX-' . date('YmdHis'))
                        ->required()
                        ->maxLength(20),
                    TextInput::make('pembeli')
                        ->label('Nama Pembeli')
                        ->maxLength(50)
                        ->nullable(),
                    DateTimePicker::make('penjualan_tanggal')
                        ->label('Tanggal')
                        ->required()
                        ->default(now()),
                    Hidden::make('user_id')
                        ->default(fn() => Auth::id()),
                ])->columns(2),

            Section::make('Detail Barang')
                ->schema([
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
                                })
                                ->columnSpan(2),
                            TextInput::make('harga')
                                ->numeric()
                                ->required()
                                ->prefix('Rp')
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(1),
                            TextInput::make('jumlah')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->default(1)
                                ->columnSpan(1),
                        ])
                        ->columns(4)
                        ->addActionLabel('+ Tambah Barang'),
                ]),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            InfoSection::make('Informasi Transaksi')
                ->schema([
                    TextEntry::make('penjualan_kode')->label('Kode Transaksi'),
                    TextEntry::make('pembeli')->label('Pembeli')->default('-'),
                    TextEntry::make('user.nama')->label('Kasir'),
                    TextEntry::make('penjualan_tanggal')->label('Tanggal')->dateTime(),
                ])->columns(2),

            InfoSection::make('Detail Barang')
                ->schema([
                    RepeatableEntry::make('details')
                        ->label('')
                        ->schema([
                            TextEntry::make('barang.barang_kode')->label('Kode'),
                            TextEntry::make('barang.barang_nama')->label('Nama Barang'),
                            TextEntry::make('harga')->label('Harga Satuan')->money('IDR'),
                            TextEntry::make('jumlah')->label('Qty'),
                            TextEntry::make('subtotal')
                                ->label('Subtotal')
                                ->getStateUsing(fn($record) => $record->harga * $record->jumlah)
                                ->money('IDR'),
                        ])->columns(5),
                ]),

            InfoSection::make('')
                ->schema([
                    TextEntry::make('total')
                        ->label('Total Pembayaran')
                        ->getStateUsing(
                            fn($record) => $record->details->sum(fn($d) => $d->harga * $d->jumlah)
                        )
                        ->money('IDR'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('penjualan_kode')->label('Kode')->searchable(),
            TextColumn::make('pembeli')->label('Pembeli')->default('-'),
            TextColumn::make('user.nama')->label('Kasir'),
            TextColumn::make('penjualan_tanggal')->label('Tanggal')->dateTime(),
            TextColumn::make('details_count')->counts('details')->label('Jml Item'),
            TextColumn::make('total')
                ->label('Total')
                ->getStateUsing(fn($record) => $record->details->sum(fn($d) => $d->harga * $d->jumlah))
                ->money('IDR'),
        ])
            ->recordUrl(fn($record) => static::getUrl('view', ['record' => $record]))
            ->actions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'view'   => Pages\ViewPenjualan::route('/{record}'),
        ];
    }
}
