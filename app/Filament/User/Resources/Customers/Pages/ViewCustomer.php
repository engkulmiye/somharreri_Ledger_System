<?php

namespace App\Filament\User\Resources\Customers\Pages;

use App\Filament\User\Resources\Customers\CustomerResource;
use App\Filament\User\Resources\Customers\Widgets\CustomerFinancialOverview;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CustomerFinancialOverview::make([
                'customer' => $this->record,
            ]),
        ];
    }
}
