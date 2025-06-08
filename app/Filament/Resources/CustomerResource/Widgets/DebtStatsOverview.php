<?php

namespace App\Filament\Resources\CustomerResource\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DebtStatsOverview extends BaseWidget
{
    public ?Customer $record = null;

    protected function getStats(): array
    {
        $debt = $this->record->debt_total;
        $totalDebt = 'Rp ' . number_format($debt, 0, ',', '.');
        $color = $debt > 0 ? 'danger' : 'success';

        return [
            Stat::make('Total Hutang', $totalDebt)
                ->color($color)
                ->description($debt > 0 ? 'Pelanggan memiliki hutang' : 'Tidak ada hutang')
                ->descriptionIcon($debt > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-check-circle')
                ->icon('heroicon-o-credit-card'),
        ];
    }
}
