<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class UsersWidget extends StatsOverviewWidget
{

    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total Users', User::count()),

            Stat::make('Total Employees', User::count()),

            Stat::make('Total Company', User::count()),
        ];
    }
}
