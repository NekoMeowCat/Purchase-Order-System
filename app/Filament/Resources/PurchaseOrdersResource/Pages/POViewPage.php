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
    public string $departmentName; // Add this property

    public function mount(Request $request): void
    {
        $pr_number = $request->route('pr_number');

        // Load purchase orders along with the supplier and department relationships
        $this->purchaseOrders = PurchaseOrders::with(['supplier', 'department']) // Load department relationship
            ->where('pr_number', $pr_number)
            ->get();

        // Get the supplier name, created_at date, and department name from the first purchase order
        $firstOrder = $this->purchaseOrders->first();
        $this->supplierName = $firstOrder->supplier->name ?? 'N/A';
        $this->createdAt = $firstOrder->created_at->format('F j, Y') ?? 'N/A'; // Format as needed
        $this->departmentName = $firstOrder->department->name ?? 'N/A'; // Get department name
    }
}
