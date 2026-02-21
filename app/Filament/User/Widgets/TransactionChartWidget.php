<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;

use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;

class TransactionChartWidget extends ChartWidget
{
    protected ?string $heading = 'New Transaction Chart';

    protected ?string $description = 'Shows total revenue grouped by month for the selected year.';

    public ?string $filter = 'today';

    protected ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;


    protected static ?int $sort = 2;

    protected function getFilters(): ?array
{
    return [
        'today' => 'Today',
        'week' => 'Last week',
        'month' => 'Last month',
        'year' => 'This year',
    ];
}
    protected function getData(): array
    {
        $data = $this->getTransactionPerMonth();

        $activeFilter = $this->filter;

        return [
            'datasets' => [
                [
                    'label' => 'Transactions created',
                    'data' => $data['transactionsPerMonth'],
                    'filter' => $activeFilter,

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
