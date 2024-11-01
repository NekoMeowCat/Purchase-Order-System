<?php

namespace App\Filament\Resources\PurchaseOrderItemsResource\Pages;

use App\Filament\Resources\PurchaseOrderItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseOrderItems extends ListRecords
{
    protected static string $resource = PurchaseOrderItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
