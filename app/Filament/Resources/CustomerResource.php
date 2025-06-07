<?php

namespace App\Filament\Resources;

use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\CustomerResource\Pages\ViewCustomer;
use App\Filament\Resources\CustomerResource\Pages\ListCustomers;
use App\Filament\Resources\CustomerResource\RelationManagers\DebtsRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\PaymentHistoriesRelationManager;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Pelanggan';

    protected static ?string $slug = 'customers';

    protected static ?string $modelLabel = 'Pelanggan';

    protected static ?string $navigationBadgeTooltip = 'Total Data Pelanggan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->validationMessages([
                        'required' => 'Nama wajib diisi',
                    ])
                    ->minLength(4)
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('Alamat')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->description(fn(Customer $record): string => $record->address ?? '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('debt_total')
                    ->label('Total Hutang')
                    ->color('danger')
                    ->prefix('Rp ')
                    ->money('IDR')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DebtsRelationManager::class,
            PaymentHistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'view' => ViewCustomer::route('/{record}'),
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
