<?php

namespace App\Filament\Resources\PurchaseOrderItemsResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PurchaseOrderItemsResource;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords\Tab;

class ListPurchaseOrderItems extends ListRecords
{
    protected static string $resource = PurchaseOrderItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PrintAction::make(),
            // CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'pending')),
            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'approved')),
            'out_for_delivery' => Tab::make('Out for Delivery')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'out_for_delivery')),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'completed')),
        ];
    }
}
