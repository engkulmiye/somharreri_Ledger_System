<?php

namespace App\Filament\User\Resources\Transactions\Pages;

use App\Filament\User\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
