<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Tiptap\Marks\Bold;



class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

            Tabs::make('Tabs')
               ->tabs([
                  Tab::make('Transaction Info')
                    ->icon(Heroicon::Bookmark)
                    ->schema([
                        TextEntry::make('date')
                    ->date()
                    ->weight('bold')
                    ->color('primary'),
                   TextEntry::make('partner_display_name')
                    ->label('Partner')
                    ->numeric()
                    ->placeholder('-')
                    ->weight('bold')
                    ->color('primary'),
                   TextEntry::make('manual_partner_name')
                    ->placeholder('-')
                    ->weight('bold')
                    ->color('primary'),
                    ]),

                  Tab::make('Balance & Status')
                      ->icon(Heroicon::CurrencyDollar)
                      ->schema([
                  TextEntry::make('type')
                    ->badge(),
                Group::make()
                   ->schema([
                    TextEntry::make('cash_ksh')
                    ->numeric()
                    ->placeholder('0')
                    ->weight('bold')
                    ->color('primary')
                    ,
                TextEntry::make('rate')
                    ->numeric()
                    ->placeholder('0')
                    ->weight('bold')
                    ->color('primary'),
                TextEntry::make('amount_usd')
                    ->numeric()
                    ->weight('bold')
                    ->color('primary'),
                   ])->columns(3),
                TextEntry::make('commission_rate')
                    ->numeric()
                    ->weight('bold')
                    ->color('primary'),
                TextEntry::make('commission_amount')
                    ->numeric()
                    ->weight('bold')
                    ->color('primary'),
                TextEntry::make('total_amount')
                    ->numeric()
                    ->weight('bold')
                    ->color('primary'),
                                TextEntry::make('previous_balance')
                                    ->label('Previous Balance')
                                    ->money('USD')
                                    ->weight('bold')
                                    ->color('gray'),
                                

                                TextEntry::make('running_ledger_balance')
                                    ->label('Running Balance')
                                    ->money('USD')
                                    ->weight('bold')
                                    ->color('success'),
                TextEntry::make('status')
                    ->badge(),
                   ]),

                  Tab::make('Moare Detials')
                    ->icon(Heroicon::InformationCircle)
                    ->schema([
                        TextEntry::make('paid_at')
                    ->date()
                    ->placeholder('-')
                    ->weight('bold')
                    ->color('primary'),
                TextEntry::make('parent_debt_id')
                     ->label('Paying Which Debt')
                     ->formatStateUsing(function ($state) {
                   $debt = \App\Models\Transaction::find($state);

                    if (! $debt) {
                      return '-';
                      }
                       return ($debt->partner?->name ?? $debt->manual_partner_name)
                      . ' (Debt #' . $debt->id . ')';
                 })
                 ->weight('bold')
                 ->color('primary'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull()
                    ->weight('bold')
                    ->color('primary'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->weight('bold')
                    ->color('primary'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->weight('bold')
                    ->color('primary'),
                    ])
               ])->columnSpanFull()->vertical()

            ]);
    }
}
