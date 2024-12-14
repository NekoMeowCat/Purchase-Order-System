<?php

namespace App\Filament\Resources\PurchaseOrdersResource\Pages;

use App\Filament\Resources\PurchaseOrdersResource;
use App\Models\PurchaseOrders;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class POViewPage extends Page
{
    protected static string $resource = PurchaseOrdersResource::class;
    protected static string $view = 'filament.resources.purchase-orders-resource.pages.p-o-view-page';
    protected static ?string $breadcrumb = "Purchase Request View";
    protected static ?string $title = ' ';

    public Collection $purchaseOrders;
    public string $supplierName;
    public string $createdAt;
    public string $userName;
    public ?string $comptrollerSignatureUrl = null;
    public ?string $comptrollerName = null;
    public ?string $vpSignatureUrl = null;
    public ?string $vpName = null;
    public ?string $headSignatureUrl = null;

    public function mount(Request $request): void
    {
        $pr_number = $request->route('pr_number');

        $this->purchaseOrders = PurchaseOrders::with(['supplier', 'user'])
            ->where('pr_number', $pr_number)
            ->get();

        $firstOrder = $this->purchaseOrders->first();

        $this->supplierName = $firstOrder->supplier->name ?? 'N/A';
        $this->createdAt = $firstOrder->created_at->format('F j, Y') ?? 'N/A';
        $this->userName = $firstOrder->user->name ?? 'N/A';
        $this->headSignatureUrl = Storage::url($firstOrder->head_signature) ?? null;
        $comptroller = User::where('position', 'Comptroller')->first();
        $this->comptrollerName = $comptroller?->name ?? 'N/A';
        $this->comptrollerSignatureUrl = $comptroller?->signature
            ? Storage::url($comptroller->signature)
            : null;

        // Get Vice President details
        $vp = User::where('position', 'Vice President')
            ->whereHas('department', function ($query) {
                $query->where('name', 'Administrative and Student Affairs');
            })
            ->first();
        $this->vpName = $vp?->name ?? 'N/A';
        $this->vpSignatureUrl = $vp?->signature
            ? Storage::url($vp->signature)
            : null;
    }
}
