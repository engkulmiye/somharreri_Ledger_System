<?php

namespace App\Filament\User\Widgets;

use App\Filament\Resources\Transactions\TransactionResource;
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
            ->query(TransactionResource::getEloquentQuery())

            ->defaultPaginationPageOption(5)

            ->defaultSort('created_at', 'desc')

            ->columns([
                TextColumn::make('date')->sortable(),

TextColumn::make('partner_display_name')
    ->label('Partner')
    ->searchable(query: function ($query, string $search) {
    $query->where('type', 'like', "%{$search}%")
        ->orWhere('manual_partner_name', 'like', "%{$search}%")
        ->orWhereHas('partner', fn ($q) =>
            $q->where('name', 'like', "%{$search}%")
        );
}),

BadgeColumn::make('type')
    ->colors([
        'danger' => 'debt',
        'success' => 'payment',
    ]) ->searchable(),

TextColumn::make('amount_usd')->money('USD'),

TextColumn::make('commission_amount')
    ->money('USD'),


BadgeColumn::make('status')
    ->colors([
        'warning' => 'open',
        'success' => 'paid',
    ])
    ->formatStateUsing(fn ($state, $record) =>
        $record && $record->type === 'debt'
            ? ucfirst($state)
            : 'paid'
    ),

TextColumn::make('paid_at')
    ->date()
    ->label('Paid On')
    ->formatStateUsing(fn ($state, $record) =>
        $record && $record->status === 'paid'
            ? $state
            : '—'
    ) ->sortable(),

TextColumn::make('total_amount')
    ->money('USD')
    ->color(fn ($record) =>
        $record->total_amount < 0 ? 'success' : 'danger'
    )
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
