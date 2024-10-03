<?php

namespace App\Filament\Resources\PurchaseOrdersResource\Pages;

use App\Filament\Resources\PurchaseOrdersResource;
use App\Models\PurchaseOrders;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;


class POViewPage extends Page
{
    protected static string $resource = PurchaseOrdersResource::class;
    protected static string $view = 'filament.resources.purchase-orders-resource.pages.p-o-view-page';
    protected static ?string $breadcrumb = "Purchase Request View";
    protected static ?string $title = ' ';

    public Collection $purchaseOrders;
    public string $supplierName;
    public string $createdAt;

    public function mount(Request $request): void
    {
        $po_number = $request->route('po_number');

        // Load purchase orders along with the supplier relationship
        $this->purchaseOrders = PurchaseOrders::with('supplier')->where('po_number', $po_number)->get();

        // Get the supplier name and created_at date from the first purchase order
        $firstOrder = $this->purchaseOrders->first();
        $this->supplierName = $firstOrder->supplier->name ?? 'N/A';
        $this->createdAt = $firstOrder->created_at->format('F j, Y') ?? 'N/A'; // Format as needed
    }
}
