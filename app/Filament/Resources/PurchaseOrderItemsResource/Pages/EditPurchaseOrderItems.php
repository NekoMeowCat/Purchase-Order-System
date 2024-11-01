<?php

namespace App\Filament\Resources\PurchaseOrderItemsResource\Pages;

use App\Filament\Resources\PurchaseOrderItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseOrderItems extends EditRecord
{
    protected static string $resource = PurchaseOrderItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
