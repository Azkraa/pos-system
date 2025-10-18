<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Sale;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicationStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Produk', Item::count()),
            Stat::make('Jumlah User', User::count()),
            Stat::make('Jumlah Penjualan', Sale::count())
        ];
    }
}
