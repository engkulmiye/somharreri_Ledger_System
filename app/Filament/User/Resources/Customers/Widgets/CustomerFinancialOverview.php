<?php

namespace App\Filament\User\Resources\Customers\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Customer;

class CustomerFinancialOverview extends BaseWidget
{
    public Customer $customer;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Debt', '$' . number_format($this->customer->total_debt, 2))
                ->description('All debt transactions')
                ->chart([9, 8, 7, 3, 4, 5, 6, 3, 5, 2])
                ->color('danger'),

            Stat::make('Total Paid', '$' . number_format($this->customer->total_paid, 2))
                ->description('All payments')
                ->chart([9, 8, 7, 3, 4, 5, 6, 3, 5, 2])
                ->color('success'),

            Stat::make('Balance', '$' . number_format($this->customer->balance, 2))
                ->description(
                    $this->customer->balance > 0
                        ? 'Outstanding balance'
                        : 'Account settled'
                )
                ->color($this->customer->balance > 0 ? 'warning' : 'success')
                ->chart([9, 8, 7, 3, 4, 5, 6, 3, 5, 2]),
        ];
    }
}
