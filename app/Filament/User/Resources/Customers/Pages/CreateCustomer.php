<?php

namespace App\Filament\User\Resources\Customers\Pages;

use App\Filament\User\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
