<?php

namespace App\Filament\Resources\PurchaseOrderItemsResource\Pages;

use App\Filament\Resources\PurchaseOrderItemsResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseOrderItems;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class InvoiceView extends ViewRecord
{
    protected static string $resource = PurchaseOrderItemsResource::class;
    protected static string $view = 'filament.resources.purchase-order-items.view-invoice';
    protected static ?string $breadcrumb = "Invoice";
    protected static ?string $title = 'Purchase Order';
    protected ?string $heading = ' ';

    public $prNumber;
    public $relatedItems;
    public ?string $directorPmoName = null;
    public ?string $directorPmoSignature = null;
    public ?string $comptrollerName = null;
    public ?string $comptrollerSignature = null;
    public ?string $vpName = null;
    public ?string $vpSignature = null;
    public ?string $prsDate = null;

    public function getRecord(): Model
    {
        $record = parent::getRecord();

        // Eager load 'supplier' and 'purchaseOrder'
        $record = $record->load('supplier', 'purchaseOrder');

        // Fetch related items
        $this->relatedItems = $record->newQuery()
            ->where('po_number', $record->po_number)
            ->with('supplier', 'purchaseOrder')
            ->get();

        $record->related_items = $this->relatedItems;

        // Fetch pr_number and prs_date
        $purchaseOrder = $record->purchaseOrder;
        $this->prNumber = $purchaseOrder ? $purchaseOrder->pr_number : 'N/A';
        $this->prsDate = $purchaseOrder && $purchaseOrder->prs_date
            ? Carbon::parse($purchaseOrder->prs_date)
            : null;

        // Fetch Director, PMO
        $directorPmo = User::where('position', 'Director, PMO')->first();
        $this->directorPmoName = $directorPmo?->name ?? 'N/A';
        $this->directorPmoSignature = $record->signature_pmo ? Storage::url($record->signature_pmo) : null;

        // Fetch Comptroller
        $comptroller = User::where('position', 'Comptroller')->first();
        $this->comptrollerName = $comptroller?->name ?? 'N/A';
        $this->comptrollerSignature = $comptroller?->signature ? Storage::url($comptroller->signature) : null;

        // Fetch Vice President in Administrative and Student Affairs
        $vp = User::where('position', 'Vice President')
            ->whereHas('department', function ($query) {
                $query->where('name', 'Administrative and Student Affairs');
            })->first();
        $this->vpName = $vp?->name ?? 'N/A';
        $this->vpSignature = $vp?->signature ? Storage::url($vp->signature) : null;

        return $record;
    }
}
