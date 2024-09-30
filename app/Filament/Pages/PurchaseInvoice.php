<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\PurchaseOrders;

class PurchaseInvoice extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.purchase-invoice';
    protected static bool $shouldRegisterNavigation = false;

    public array $records = [];
    public ?string $po_number = null;

    public function mount($po_number): void
    {
        $this->po_number = $po_number;
        if ($this->po_number) {
            $this->records = PurchaseOrders::where('po_number', $this->po_number)->get()->toArray();
        } else {
            abort(404, 'No purchase orders found for this PO number.');
        }
    }


    public function getTitle(): string
    {
        return "Invoices for PO Number: {$this->po_number}";
    }
}
