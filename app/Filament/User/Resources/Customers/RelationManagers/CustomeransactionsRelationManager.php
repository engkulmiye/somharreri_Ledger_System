<?php

namespace App\Filament\User\Resources\Customers\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Actions\Action;

class CustomeransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'customertransactions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('CustomerTransaction')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('CustomerTransaction')
            ->headerActions([
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn() => route('customer.transactions.pdf', [
                        'customer' => $this->getOwnerRecord()->id,
                        'filters'  => request()->all(),   // pass table filters
                    ]))
                    ->openUrlInNewTab(),
            ])
            ->columns([
                TextColumn::make('date')->date(),
                TextColumn::make('manual_partner_name')
                    ->label('Description'),
                TextColumn::make('type')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'debt'
                            ? 'danger'
                            : 'success'
                    ),
                TextColumn::make('total_amount')
                    ->money('USD')
                    ->weight('bold'),
                TextColumn::make('commission_amount')->money('USD'),
                TextColumn::make('remaining_amount')
                    ->money('USD')
                    ->color(
                        fn($state) =>
                        $state > 0 ? 'warning' : 'success'
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
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
