<?php

namespace App\Filament\User\Widgets;


use App\Filament\User\Resources\CustomerTransactions\CustomerTransactionResource;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;


use Filament\Tables\Columns\BadgeColumn;

class LatestTransactionsWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(CustomerTransactionResource::getEloquentQuery())

            ->defaultPaginationPageOption(5)

            ->defaultSort('created_at', 'desc')

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
                    ->weight('bold')
                    ->money('USD'),


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
                    ->weight('bold')
                    ->money('USD')
                    ->color(
                        fn($record) =>
                        $record->total_amount < 0 ? 'success' : 'danger'
                    ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
