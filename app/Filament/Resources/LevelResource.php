<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LevelResource\Pages;
use App\Models\Level;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LevelResource extends Resource
{
    protected static ?string $model = Level::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $label = 'Level';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('level_kode')
                ->required()
                ->maxLength(10),
            TextInput::make('level_nama')
                ->required()
                ->maxLength(100),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('level_id')->label('ID')->sortable(),
            TextColumn::make('level_kode')->searchable(),
            TextColumn::make('level_nama')->searchable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLevels::route('/'),
            'create' => Pages\CreateLevel::route('/create'),
            'edit'   => Pages\EditLevel::route('/{record}/edit'),
        ];
    }
}
