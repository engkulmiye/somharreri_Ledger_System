<?php

namespace App\Filament\User\Resources\Customers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Customer Information')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Customer Name')
                            ->weight('bold')
                            ->size('Large'),

                        TextEntry::make('phone')
                            ->icon('heroicon-o-phone'),

                        TextEntry::make('location')
                            ->icon('heroicon-o-map-pin'),


                        TextEntry::make('created_at')
                            ->dateTime(),


                    ])
                    ->columns(2)
                    ->columnSpanFull(),

            ]);
    }
}
