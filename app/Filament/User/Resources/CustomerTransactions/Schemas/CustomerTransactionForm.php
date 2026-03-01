<?php

namespace App\Filament\User\Resources\CustomerTransactions\Schemas;

use App\Models\Customer;
use App\Models\CustomerTransaction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomerTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                DatePicker::make('date')->required(),
                Select::make('type')
                    ->options([
                        'debt' => 'Galaal Owes',
                        'payment' => 'Galaal Paid',
                    ])
                    ->required()
                    ->reactive(),

                Select::make('customer_id')
                    ->label('Customer Name')
                    ->options(Customer::select()->pluck('name', 'id'))
                    ->searchable()
                    ->reactive(),

                TextInput::make('manual_partner_name')
                    ->label('Other Partner')
                    ->required(),


                Checkbox::make('has_money')
                    ->label('Money')
                    ->reactive(),

                TextInput::make('cash_ksh')
                    ->label('Cash (KSH)')
                    ->numeric()
                    ->live()
                    ->visible(fn($get) => $get('has_money'))
                    ->afterStateUpdated(function ($get, $set) {
                        $cash = (float) $get('cash_ksh');
                        $rate = (float) $get('rate');

                        if ($cash > 0 && $rate > 0) {
                            $set('amount_usd', round($cash / $rate, 2));
                        }
                    }),

                TextInput::make('rate')
                    ->label('Rate')
                    ->numeric()
                    ->live()
                    ->visible(fn($get) => $get('has_money'))
                    ->afterStateUpdated(function ($get, $set) {
                        $cash = (float) $get('cash_ksh');
                        $rate = (float) $get('rate');

                        if ($cash > 0 && $rate > 0) {
                            $set('amount_usd', round($cash / $rate, 2));
                        }
                    }),


                TextInput::make('amount_usd')
                    ->label('Amount (USD)')
                    ->numeric()
                    ->live()
                    ->required(),

                TextInput::make('commission_rate')
                    ->numeric()
                    ->default(1.2)
                    ->visible(fn($get) => $get('type') === 'debt'),

                Placeholder::make('customer_open_balance')
                    ->label('Current Open Balance')
                    ->content(function (callable $get) {

                        $customerId = $get('customer_id');

                        if (!$customerId) {
                            return '$0.00';
                        }

                        $total = CustomerTransaction::where('customer_id', $customerId)
                            ->where('type', 'debt')
                            ->where('status', 'open')
                            ->sum('remaining_amount');

                        return '$' . number_format($total, 2);
                    })
                    ->visible(fn(callable $get) => $get('type') === 'payment'),

                Textarea::make('notes'),
            ]);
    }
}
