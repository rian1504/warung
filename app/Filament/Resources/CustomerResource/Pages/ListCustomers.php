<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected ?string $heading = 'Daftar Pelanggan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pelanggan')
                ->icon('heroicon-o-plus')
                ->modalHeading('Tambah Pelanggan Baru')
                ->modalSubmitActionLabel('Simpan Pelanggan')
                ->successNotificationTitle('Pelanggan berhasil ditambahkan'),
        ];
    }
}
