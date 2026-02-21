<?php

namespace App\Filament\Pages;

use App\Models\Transaction;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;


class MonthlyStatement extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected string $view = 'filament.pages.monthly-statement';

    protected static string|\UnitEnum|null $navigationGroup = 'Bank Accounts';
    protected static ?string $navigationLabel = 'Monthly Statement';


    protected static ?int $navigationSort = 2;



    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                   ->whereMonth('date', now()->month)
            )
            ->columns([
                Tables\Columns\TextColumn::make('date')->date(),
                Tables\Columns\TextColumn::make('partner_name'),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('amount_usd')->money('USD'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->filters([
            SelectFilter::make('month')
                ->label('Month')
                ->options([
                    1 => 'January',
                    2 => 'February',
                    3 => 'March',
                    4 => 'April',
                    5 => 'May',
                    6 => 'June',
                    7 => 'July',
                    8 => 'August',
                    9 => 'September',
                    10 => 'October',
                    11 => 'November',
                    12 => 'December',
                ])
                ->query(fn ($query, $value) =>
                    $query->whereMonth('date', $value)
                ),

        ])
        ->defaultSort('date', 'desc');
    }
}
