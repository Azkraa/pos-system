<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Carbon\Carbon;

class TotalUsersChart extends ChartWidget
{
    protected ?string $heading = 'Total Users';

    protected ?string $pollingInterval = '30s';

    public ?string $filter = 'day';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'day' => 'Day',
            'month' => 'Month',
            'year' => 'Year',
        ];
    }

    protected function getData(): array
    {
        $labels = [];
        $values = [];

        if ($this->filter === 'day') {
            $users = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = $users->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray();
            $values = $users->pluck('total')->toArray();
        } elseif ($this->filter === 'month') {
            $users = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $labels = $users->pluck('month')->map(fn($m) => Carbon::create()->month($m)->format('M'))->toArray();
            $values = $users->pluck('total')->toArray();
        } else { // year
            $users = User::selectRaw('YEAR(created_at) as year, COUNT(*) as total')
                ->groupBy('year')
                ->orderBy('year')
                ->get();

            $labels = $users->pluck('year')->toArray();
            $values = $users->pluck('total')->toArray();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Users',
                    'data' => $values,
                    'borderColor' => '#F59E0B',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                ],
            ],
        ];
    }
}
