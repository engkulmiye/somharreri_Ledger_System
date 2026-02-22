<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use Illuminate\Support\Number;


use App\Models\Transaction;


class LedgerStats extends StatsOverviewWidget

{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;

    protected function getStats(): array
    {

        $openDebts = Transaction::where('type', 'debt')
            ->where('status', 'open')
            ->get();

        $totalRemaining = $openDebts->sum(fn($debt) => $debt->remaining_amount);

        $totalPaid = Transaction::where('type', 'payment')->sum('total_amount');

        $totalCommission = Transaction::sum('commission_amount');

        return [
            Stat::make('Wadarta deyn', Number::currency($totalRemaining, 'USD'))
                ->description('Deynta Company ee aan la bixin')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 2]),

            Stat::make('Wadarta La Bixiyay', Number::currency($totalPaid, 'USD'))
                ->description('Wadarta lacagaha la ururiyey')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([2, 4, 6, 5, 7, 6, 8, 9]),

            Stat::make(
                'Wadarta Komishanka',
                Number::currency($totalCommission, 'USD')
            )->description('Dakhliga komishanka saafiga ah')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary')
                ->chart([3, 5, 4, 6, 7, 5, 6, 8]),

        ];
    }
}
