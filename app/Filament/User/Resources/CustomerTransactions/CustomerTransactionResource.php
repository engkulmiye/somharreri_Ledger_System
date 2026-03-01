<?php

namespace App\Filament\User\Resources\CustomerTransactions;

use App\Filament\User\Resources\CustomerTransactions\Pages\CreateCustomerTransaction;
use App\Filament\User\Resources\CustomerTransactions\Pages\EditCustomerTransaction;
use App\Filament\User\Resources\CustomerTransactions\Pages\ListCustomerTransactions;
use App\Filament\User\Resources\CustomerTransactions\Pages\ViewCustomerTransaction;
use App\Filament\User\Resources\CustomerTransactions\Schemas\CustomerTransactionForm;
use App\Filament\User\Resources\CustomerTransactions\Schemas\CustomerTransactionInfolist;
use App\Filament\User\Resources\CustomerTransactions\Tables\CustomerTransactionsTable;
use App\Models\CustomerTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerTransactionResource extends Resource
{
    protected static ?string $model = CustomerTransaction::class;

    protected static ?int $navigationSort = 2;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    public static function form(Schema $schema): Schema
    {
        return CustomerTransactionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerTransactionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerTransactionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerTransactions::route('/'),
            'create' => CreateCustomerTransaction::route('/create'),
            'view' => ViewCustomerTransaction::route('/{record}'),
            'edit' => EditCustomerTransaction::route('/{record}/edit'),
        ];
    }
}
