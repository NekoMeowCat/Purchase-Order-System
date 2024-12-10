<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PurchaseOrderItems;

class PurchaseOrderItemStatusGraph extends ChartWidget
{
    protected static ?string $heading = 'Purchase Orders';

    protected function getData(): array
    {
        $data = PurchaseOrderItems::groupBy('status')
            ->selectRaw('count(*) as count, status')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('count')->toArray(),
                    'fill' => true,
                    'backgroundColor' => '#D4EBF8',
                ]
            ],
            'labels' => $data->pluck('status')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
