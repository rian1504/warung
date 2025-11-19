<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\Pages\ListProducts;
use App\Filament\Resources\ProductResource\Pages\CreateProduct;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Barang';

    protected static ?string $slug = 'products';

    protected static ?string $modelLabel = 'Barang';

    protected static ?string $navigationBadgeTooltip = 'Total Data Barang';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Barang')
                    ->required()
                    ->maxLength(255),
                TextInput::make('quantity')
                    ->label('Jumlah Barang')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Select::make('unit')
                    ->label('Satuan')
                    ->required()
                    ->options([
                        'pcs' => 'Pcs',
                        'kg' => 'Kg',
                        'liter' => 'Liter',
                        'bks' => 'Bungkus',
                        'pck' => 'Pack',
                        'bal' => 'Bal',
                        'dus' => 'Dus',
                    ]),
                TextInput::make('price')
                    ->label('Harga Barang')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp '),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Harga Barang')
                    ->money('idr', true)
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Jumlah Barang')
                    ->toggleable(),
                TextColumn::make('unit')
                    ->label('Satuan Barang')
                    ->toggleable(),
            ])
            ->defaultSort('name', 'asc')
            ->paginated(['all'])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
