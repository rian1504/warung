<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\Widgets\DebtStatsOverview;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            DebtStatsOverview::make([
                'record' => $this->getRecord(),
            ]),
        ];
    }
}
