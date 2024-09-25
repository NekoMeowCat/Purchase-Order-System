<?php

namespace App\Filament\Resources\PurchaseOrdersResource\Pages;

use App\Filament\Resources\PurchaseOrdersResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseOrders extends ListRecords
{
    use  \EightyNine\Approvals\Traits\HasApprovalHeaderActions;

    protected static string $resource = PurchaseOrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
