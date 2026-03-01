<?php

namespace App\Filament\User\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->tel(),

                        TextInput::make('location')
                            ->required(),
                    ])->columns(2),

                Section::make('Financial Summary')
                    ->schema([
                        TextInput::make('total_debt')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('total_paid')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('balance')
                            ->numeric()
                            ->disabled(),
                    ])->columns(3),
            ]);
    }
}
