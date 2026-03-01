<?php

namespace App\Filament\User\Resources\CustomerTransactions\Pages;

use App\Filament\User\Resources\CustomerTransactions\CustomerTransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerTransaction extends CreateRecord
{
    protected static string $resource = CustomerTransactionResource::class;
}
