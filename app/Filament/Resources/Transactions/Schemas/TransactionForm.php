<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;



use App\Models\Partner;
use App\Models\Transaction;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')->required(),
Select::make('type')
    ->options([
        'debt' => 'Company Owes',
        'payment' => 'Company Paid',
    ])
    ->required()
    ->reactive(),

Select::make('partner_id')
    ->label('Partner')
    ->options(Partner::where('is_active', true)->pluck('name', 'id'))
    ->searchable()
    ->reactive()
    ->afterStateUpdated(fn ($set) => $set('manual_partner_name', null)),

TextInput::make('manual_partner_name')
    ->label('Other Partner')
    ->visible(fn ($get) => empty($get('partner_id')))
    ->required(fn ($get) => empty($get('partner_id'))),


Checkbox::make('has_money')
    ->label('Money')
    ->reactive(),

TextInput::make('cash_ksh')
    ->label('Cash (KSH)')
    ->numeric()
    ->live()
    ->visible(fn ($get) => $get('has_money'))
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
    ->visible(fn ($get) => $get('has_money'))
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
    ->required(),

TextInput::make('commission_rate')
    ->numeric()
    ->default(1.2)
    ->visible(fn ($get) => $get('type') === 'debt'),

Select::make('parent_debt_id')
    ->label('Paying Which Debt?')
    ->options(function () {
        return Transaction::where('type', 'debt')
            ->where('status', 'open')
            ->get()
            ->mapWithKeys(function ($tx) {
                $name = $tx->partner?->name ?? $tx->manual_partner_name ?? 'Unknown';

                return [
                    $tx->id => $name . ' (Debt #' . $tx->id . ')',
                ];
            });
    })
    ->searchable()
    ->visible(fn ($get) => $get('type') === 'payment')
    ->required(fn ($get) => $get('type') === 'payment'),

Textarea::make('notes'),
            ]);
    }



}
