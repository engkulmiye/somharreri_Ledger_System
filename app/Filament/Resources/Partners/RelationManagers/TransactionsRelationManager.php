<?php

namespace App\Filament\Resources\Partners\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                 TextColumn::make('id')->label('ID')->sortable(),

            TextColumn::make('type')
                ->badge()
                ->colors([
                    'danger' => 'debt',
                    'success' => 'payment',
                ]),

            TextColumn::make('total_amount')
                ->label('Amount')
                ->money('USD')
                ->sortable(),

            TextColumn::make('remaining_amount')
                ->label('Remaining')
                ->money('USD')
                ->color('danger'),

            TextColumn::make('date')
                ->date()
                ->sortable(),

            TextColumn::make('status')
                ->badge()
                ->color(fn ($state) => $state === 'open' ? 'danger' : 'success'),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->recordActions([
                EditAction::make(),
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
