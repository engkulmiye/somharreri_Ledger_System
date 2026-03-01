<?php

namespace App\Filament\User\Resources\CustomerTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;



class CustomerTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('date')->sortable(),

                TextColumn::make('partner_display_name')
                    ->label('Customer Name')
                    ->searchable(query: function ($query, string $search) {
                        $query->where('type', 'like', "%{$search}%")
                            ->orWhere('manual_partner_name', 'like', "%{$search}%")
                            ->orWhereHas(
                                'customer',
                                fn($q) =>
                                $q->where('name', 'like', "%{$search}%")
                            );
                    })
                    ->badge(fn($record) => (bool) $record->manual_partner_name)
                    ->color(fn($record) => $record->manual_partner_name ? 'blue' : 'gray'),

                TextColumn::make('manual_partner_name')
                    ->label('Description')
                    ->searchable(),

                BadgeColumn::make('type')
                    ->colors([
                        'danger' => 'debt',
                        'success' => 'payment',
                    ])->searchable(),

                TextColumn::make('amount_usd')
                    ->money('USD')
                    ->weight('bold'),

                TextColumn::make('commission_amount')
                    ->money('USD')
                    ->weight('bold'),


                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'open',
                        'success' => 'paid',
                    ])
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record && $record->type === 'debt'
                            ? ucfirst($state)
                            : '-'
                    ),

                TextColumn::make('total_amount')
                    ->money('USD')
                    ->weight('bold')
                    ->color(
                        fn($record) =>
                        $record->total_amount < 0 ? 'success' : 'danger'
                    ),
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

                ])
            ]);
    }
}
