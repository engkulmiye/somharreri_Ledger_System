<?php

namespace App\Filament\User\Widgets;

use App\Models\CustomerTransaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


use Illuminate\Support\Number;

class CompanyInfoWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {

        $openDebts = CustomerTransaction::where('type', 'debt')
            ->select()
            ->get();

        $totalRemaining = $openDebts->sum(fn($debt) => $debt->remaining_amount);

        $totalPaid = CustomerTransaction::where('type', 'payment')->sum('total_amount');

        $totalCommission = CustomerTransaction::sum('commission_amount');

        return [
            //
            Stat::make('Wadarta deyn', Number::currency($totalRemaining, 'USD'))
                ->description('Deynta Customer ee aan la bixin')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 2]),

            Stat::make('Wadarta La Bixiyay', Number::currency($totalPaid, 'USD'))
                ->description('Wadarta lacagaha la ururiyey')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([2, 4, 6, 5, 7, 6, 8, 9]),

            Stat::make('Wadarta Komishanka', Number::currency($totalCommission, 'USD'))
                ->description('Dakhliga komishanka saafiga ah')
                ->descriptionIcon('heroicon-m-bookmark')
                ->color('info')
                ->chart([2, 4, 6, 5, 7, 6, 8, 9]),

        ];
    }
}
