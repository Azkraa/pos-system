<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use App\Models\Sale;
use Carbon\Carbon;

class TotalSalesChart extends ChartWidget
{
    protected ?string $heading = 'Total Sales';

    protected ?string $pollingInterval = '30s'; // optional auto-refresh

    // Filter: day, month, year
    public ?string $filter = 'day';


    protected function getType(): string
    {
        return 'bar';
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
            $sales = Sale::selectRaw('DATE(created_at) as date, SUM(total) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = $sales->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray();
            $values = $sales->pluck('total')->toArray();
        } elseif ($this->filter === 'month') {
            $sales = Sale::selectRaw('MONTH(created_at) as month, SUM(total) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $labels = $sales->pluck('month')->map(fn($m) => Carbon::create()->month($m)->format('M'))->toArray();
            $values = $sales->pluck('total')->toArray();
        } else { // year
            $sales = Sale::selectRaw('YEAR(created_at) as year, SUM(total) as total')
                ->groupBy('year')
                ->orderBy('year')
                ->get();

            $labels = $sales->pluck('year')->toArray();
            $values = $sales->pluck('total')->toArray();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Sales',
                    'data' => $values,
                    'backgroundColor' => '#4F46E5',
                ],
            ],
        ];
    }
}
