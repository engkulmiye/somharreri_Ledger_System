<?php

namespace App\Filament\User\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('phone'),

                TextColumn::make('total_debt')
                    ->money('USD')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('total_paid')
                    ->money('USD')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('balance')
                    ->money('USD')
                    ->weight('bold')
                    ->sortable()
                    ->color(fn($state) => $state > 0 ? 'danger' : 'success'),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
