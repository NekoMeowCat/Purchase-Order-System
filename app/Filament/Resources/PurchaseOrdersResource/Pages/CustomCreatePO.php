<?php

namespace App\Filament\Resources\PurchaseOrdersResource\Pages;

use App\Filament\Resources\PurchaseOrdersResource;
use Filament\Resources\Pages\Page;
use App\Models\PurchaseOrders;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Carbon\Carbon;


class CustomCreatePO extends Page implements HasForms
{
    use InteractsWithForms;

    protected ?string $heading = 'Purchase Order';
    protected static ?string $breadcrumb = "Purchase Order Application";

    protected static string $resource = PurchaseOrdersResource::class;

    protected static string $view = 'filament.resources.purchase-orders-resource.pages.custom-create-p-o';

    public $rows = [];
    public $sub_total = 0;
    public $tax = 2; // Set the tax value as needed
    public $over_all_total = 0;

    public function mount(): void
    {
        // Initialize with one empty row
        $this->rows = [
            [
                'itemNo' => '',
                'description' => '',
                'quantity' => '',
                'unitPrice' => '',
                'total' => '',
            ]
        ];
    }

    public function save()
    {
        // Get the current PO date using Carbon
        $po_date = Carbon::now()->format('Y-m-d'); // Change format if needed

        // Here, $this->rows will contain the data from each row
        $data = $this->rows;

        // Calculate overall totals
        $sub_total = collect($data)->sum('total');
        $tax = 2; // Your tax logic
        $over_all_total = $sub_total + $tax;

        // Debugging: Check the values being inserted
        // dd($data, $po_date); // Ensure this shows the correct values before proceeding

        // Save the rows into the database
        foreach ($data as $row) {
            PurchaseOrders::create([
                'item_no' => $row['itemNo'],
                'description' => $row['description'],
                'quantity' => $row['quantity'],
                'unit_price' => $row['unitPrice'],
                'total' => $row['total'],
                'sub_total' => $sub_total,
                'tax' => $tax,
                'over_all_total' => $over_all_total,
                'po_date' => $po_date, // Include the PO date using Carbon
                // Add other fields if necessary
            ]);
        }

        Notification::make()
            ->title('Purchase Order has been created')
            ->success()
            ->send();

        $this->redirect(PurchaseOrdersResource::getUrl('index'));
    }
}
