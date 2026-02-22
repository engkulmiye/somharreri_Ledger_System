<?php

namespace App\Filament\Widgets;


use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TransactionPieChartWidget extends ChartWidget
{
    protected ?string $heading = 'Transaction Pie Chart Widget';

    protected static ?int $sort = 2;

    protected ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected function getData(): array
    {
        $stats = Transaction::select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $stats->pluck('total')->toArray(),
                    'label' => 'Transactions'

                ],
            ],
            'labels' => $stats->pluck('type')
                ->map(fn($t) => ucfirst($t))
                ->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
