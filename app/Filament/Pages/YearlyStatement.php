<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use App\Models\Transaction;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;

class YearlyStatement extends Page implements HasTable
{
   use Tables\Concerns\InteractsWithTable;


   protected static string|\UnitEnum|null $navigationGroup = 'Bank Accounts';
    protected static ?string $navigationLabel = 'Yearly Statement';


    protected static ?int $navigationSort = 3;


    protected string $view = 'filament.pages.monthly-statement';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->whereYear('date', now()->year)
            )
            ->columns([
                TextColumn::make('date')->date(),
                TextColumn::make('partner_display_name'),
                TextColumn::make('type')->badge(),
                TextColumn::make('total_amount')->money('USD'),
                TextColumn::make('status')->badge(),
            ]);
    }
}
