<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseOrders;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;

class CompletedPurchaseOrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Completed Purchase Request';

    protected function getData(): array
    {
        $data = PurchaseOrders::whereHas('approvalStatus')
            ->where('items_saved', 1)
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->get()
            ->filter(function ($record) {
                return $record->isApprovalCompleted();
            })
            ->groupBy(function ($record) {
                return $record->created_at->format('Y-m-d');
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->sortKeys();

        return [
            'datasets' => [
                [
                    'label' => 'Completed Purchase Orders',
                    'data' => $data->values()->toArray(),
                    'borderColor' => '#10B981',
                    'fill' => true,
                    'backgroundColor' => '#E9EED9',
                ]
            ],
            'labels' => $data->keys()->map(function ($date) {
                return Carbon::parse($date)->format('M d');
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'stepSize' => 1, // Ensure Y-axis increments in whole numbers
                        'precision' => 0, // Force ticks to show whole numbers only
                    ],
                    'beginAtZero' => true, // Start the Y-axis at 0
                ],
            ],
        ];
    }
}
