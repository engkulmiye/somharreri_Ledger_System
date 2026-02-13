<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;

use Illuminate\Support\Number;

class CompanyInfoWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {

        $openDebts = Transaction::where('type', 'debt')
            ->where('status', 'open')
            ->get();

        $totalRemaining = $openDebts->sum(fn ($debt) => $debt->remaining_amount);

        $totalPaid = Transaction::where('type', 'payment')->sum('total_amount');

        $totalCommission = Transaction::sum('commission_amount');

        return [
            //
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

              Stat::make('Wadarta Transaction ka', Transaction::count())
              ->description('Wadarta Transaction Receipt ka')
              ->descriptionIcon('heroicon-m-bookmark')
              ->color('info')
              ->chart([2, 4, 6, 5, 7, 6, 8, 9]),


        ];
    }
}
