<?php

namespace App\Filament\Resources\Transactions\Tables;


use App\Filament\Exports\TransactionExporter;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;



class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('date')->sortable(),

                TextColumn::make('partner_display_name')
                    ->label('Partner')
                    ->searchable(query: function ($query, string $search) {
                        $query->where('type', 'like', "%{$search}%")
                            ->orWhere('manual_partner_name', 'like', "%{$search}%")
                            ->orWhereHas(
                                'partner',
                                fn($q) =>
                                $q->where('name', 'like', "%{$search}%")
                            );
                    }),

                BadgeColumn::make('type')
                    ->colors([
                        'danger' => 'debt',
                        'success' => 'payment',
                    ])->searchable(),

                TextColumn::make('amount_usd')->money('USD'),

                TextColumn::make('commission_amount')
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

                TextColumn::make('paid_at')
                    ->date()
                    ->label('Paid On')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record && $record->status === 'paid'
                            ? $state
                            : '—'
                    )->sortable(),

                TextColumn::make('total_amount')
                    ->money('USD')
                    ->color(
                        fn($record) =>
                        $record->total_amount < 0 ? 'success' : 'danger'
                    ),
            ])
            ->filters([
                //
                Filter::make('date')
                    ->label('Creation date')
                    ->schema([
                        DatePicker::make('date')
                            ->label('Select Date')
                    ])
                    ->query(function ($query, $data) {
                        return $query
                            ->when($data['date'], function ($q, $data) {
                                $q->whereDate('date', $data);
                            });
                    })
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(TransactionExporter::class),

                    ActionsAction::make('pdf')
                        ->label('Export PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('danger')
                        ->url(route('export.transactions.pdf'))
                        ->openUrlInNewTab(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
