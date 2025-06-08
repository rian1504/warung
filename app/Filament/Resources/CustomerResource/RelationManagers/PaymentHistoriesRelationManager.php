<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PaymentHistory;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class PaymentHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'payment_histories';

    protected static ?string $title = 'Riwayat Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('amount')
                    ->label('Jumlah Pembayaran')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(1)
                    ->validationMessages([
                        'required' => 'Jumlah Pembayaran wajib diisi',
                    ]),
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->required()
                    ->displayFormat('l, d F Y')
                    ->native(false)
                    ->maxDate(now())
                    ->closeOnDateSelection()
                    ->suffixIcon('heroicon-o-calendar')
                    ->default(fn(): string => now()->format('Y-m-d'))
                    ->validationMessages([
                        'required' => 'Tanggal wajib diisi',
                    ]),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->autosize(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->color('success')
                    ->money('IDR')
                    ->description(fn(PaymentHistory $record): string => $record->description ?? '-')
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('l, d F Y')
                    ->sortable(),

            ])
            ->filters([
                Filter::make('date')
                    ->form([
                        DatePicker::make('date')
                            ->label('Tanggal Bayar')
                            ->displayFormat('l, d F Y')
                            ->native(false)
                            ->maxDate(now())
                            ->closeOnDateSelection()
                            ->suffixIcon('heroicon-o-calendar'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', $date),
                            );
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Pembayaran')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Tambah Pembayaran Baru')
                    ->modalSubmitActionLabel('Simpan Pembayaran')
                    ->successNotificationTitle('Pembayaran berhasil ditambahkan')
                    ->mutateFormDataUsing(function (array $data): array {
                        $customer = $this->getOwnerRecord();
                        $debt = $customer->debt_total;

                        $debtTotal = $debt - (int) $data['amount'];

                        // Update the customer's debt total
                        Customer::where('id', $customer->id)
                            ->update(['debt_total' => $debtTotal]);

                        return $data;
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data, PaymentHistory $record): array {
                        $customer = $this->getOwnerRecord();
                        $debt = $customer->debt_total;

                        $debtOriginal = $debt + (int) $record->amount;
                        $debtTotal = $debtOriginal - (int) $data['amount'];

                        // Update the customer's debt total
                        Customer::where('id', $customer->id)
                            ->update(['debt_total' => $debtTotal]);

                        return $data;
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
