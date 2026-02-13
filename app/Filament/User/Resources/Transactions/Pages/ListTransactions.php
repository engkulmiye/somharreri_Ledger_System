<?php

namespace App\Filament\User\Resources\Transactions\Pages;

use App\Filament\User\Resources\Transactions\TransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
{
    return [
        \App\Filament\User\Widgets\CompanyInfoWidget::class,
    ];
}
}
