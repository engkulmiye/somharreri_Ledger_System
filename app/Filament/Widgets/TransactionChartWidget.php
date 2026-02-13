<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class TransactionChartWidget extends ChartWidget
{
    protected ?string $heading = 'New Transaction Chart';

    protected static ?int $sort = 2;
    protected function getData(): array
    {
        $data = $this->getTransactionPerMonth();

        return [
            'datasets' => [
                [
                    'label' => 'Transactions created',
                    'data' => $data['transactionsPerMonth']
                ],
            ],
            'labels' => $data['months'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getTransactionPerMonth(): array

    {
       $now = Carbon::now();

       $transactionsPerMonth = [];

       $months = collect(range(1, 12))->map(function($month) use ($now, &$transactionsPerMonth) {

       $count = Transaction::whereMonth('created_at', Carbon::parse($now->month($month)->format('Y-m')))->count();

       $transactionsPerMonth[] = $count;

       return $now->month($month)->format('M');

       })->toArray();

       return [
         'transactionsPerMonth' => $transactionsPerMonth,
         'months' => $months
       ];

    }
}
