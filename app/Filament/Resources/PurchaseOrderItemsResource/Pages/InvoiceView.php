<?php

namespace App\Filament\Resources\PurchaseOrderItemsResource\Pages;

use App\Filament\Resources\PurchaseOrderItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class InvoiceView extends ViewRecord
{
    protected static string $resource = PurchaseOrderItemsResource::class;

    protected static string $view = 'filament.resources.purchase-order-items.view-invoice';

    public function getRecord(): Model
    {
        $record = parent::getRecord();

        // Eager load the supplier and related items
        $record = $record->load('supplier');

        // Fetch all items with the same po_number
        $relatedItems = $record->newQuery()
            ->where('po_number', $record->po_number)
            ->with('supplier')
            ->get();

        // Attach related items to the main record
        $record->related_items = $relatedItems;

        return $record;
    }
}
