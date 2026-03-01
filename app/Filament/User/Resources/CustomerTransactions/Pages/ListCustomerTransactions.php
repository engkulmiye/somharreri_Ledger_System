<?php

namespace App\Filament\User\Resources\CustomerTransactions\Pages;

use App\Filament\User\Resources\CustomerTransactions\CustomerTransactionResource;
use App\Filament\User\Widgets\CompanyInfoWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerTransactions extends ListRecords
{
    protected static string $resource = CustomerTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CompanyInfoWidget::class,
        ];
    }
}
