<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Suppliers;
use App\Models\Departments;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::count())
                ->color('success')
                ->icon('heroicon-s-users')
                ->description('Total number of users registered'),
            Stat::make('Departments', Departments::count())
                ->color('warning')
                ->icon('heroicon-s-home-modern')
                ->description('Total number of Departments'),
            Stat::make('Suppliers', Suppliers::count())
                ->color('primary')
                ->icon('heroicon-s-user-group')
                ->description('Total number of Suppliers'),
        ];
    }
}
