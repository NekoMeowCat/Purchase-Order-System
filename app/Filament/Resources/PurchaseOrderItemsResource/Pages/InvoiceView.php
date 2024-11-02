<?php

namespace App\Filament\Resources\PurchaseOrderItemsResource\Pages;

use App\Filament\Resources\PurchaseOrderItemsResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseOrderItems;

class InvoiceView extends ViewRecord
{
    protected static string $resource = PurchaseOrderItemsResource::class;
    protected static string $view = 'filament.resources.purchase-order-items.view-invoice';
    protected static ?string $breadcrumb = "Invoice";
    protected static ?string $title = 'Purchase Order';
    protected ?string $heading = ' ';

    public $departmentName;
    public $prNumber;

    public function getRecord(): Model
    {
        $record = parent::getRecord();

        $record = $record->load('supplier', 'purchaseOrder.department');

        // Fetch related items
        $relatedItems = $record->newQuery()
            ->where('po_number', $record->po_number)
            ->with('supplier', 'purchaseOrder.department')
            ->get();

        $record->related_items = $relatedItems;

        // Fetch department name and pr_number
        $purchaseOrder = $record->purchaseOrder;
        $this->departmentName = $purchaseOrder ? $purchaseOrder->department->name : 'N/A';
        $this->prNumber = $purchaseOrder ? $purchaseOrder->pr_number : 'N/A';

        return $record;
    }
}
