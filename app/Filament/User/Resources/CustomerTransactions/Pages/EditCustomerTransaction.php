<?php

namespace App\Filament\User\Resources\CustomerTransactions\Pages;

use App\Filament\User\Resources\CustomerTransactions\CustomerTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerTransaction extends EditRecord
{
    protected static string $resource = CustomerTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
