<?php

namespace App\Filament\Resources\PurchaseOrdersResource\Pages;

use App\Filament\Resources\PurchaseOrdersResource;
use Filament\Resources\Pages\Page;
use App\Models\PurchaseOrders;
use App\Models\Suppliers;
use App\Models\Departments;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Carbon\Carbon;
use Filament\Forms\Components\Select;



class CustomCreatePO extends Page implements HasForms
{
    use InteractsWithForms;

    protected ?string $heading = 'Purchase Order';
    protected static ?string $breadcrumb = "Purchase Order Application";

    protected static string $resource = PurchaseOrdersResource::class;

    protected static string $view = 'filament.resources.purchase-orders-resource.pages.custom-create-p-o';

    public $rows = [];
    public $sub_total = 0;
    public $tax = 2;
    public $over_all_total = 0;
    public $po_number;
    public $suppliers;
    public $departments;
    public $department_id;
    public $supplier_id;


    public function mount(): void
    {
        $this->suppliers = Suppliers::all();
        $this->departments = Departments::all();
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

        $this->po_number = $this->generatePoNumber();
    }

    private function generatePoNumber(): string
    {
        // Get the current month abbreviation
        $monthAbbr = strtoupper(date('M'));

        // Generate a sequence number (you can modify this logic as per your requirements)
        $sequence = str_pad(rand(0, 999), 2, '0', STR_PAD_LEFT);

        return $monthAbbr . $sequence;
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

        // dd($data, $po_date); // Ensure this shows the correct values before proceeding

        foreach ($data as $row) {
            // dd($row);
            PurchaseOrders::create([
                'item_no' => $row['itemNo'],
                'description' => $row['description'],
                'quantity' => $row['quantity'],
                'unit_price' => $row['unitPrice'],
                'total' => $row['total'],
                'sub_total' => $sub_total,
                'tax' => $tax,
                'over_all_total' => $over_all_total,
                'po_date' => $po_date,
                'po_number' => $this->po_number,
                'supplier_id' => $this->supplier_id,
                'department_id' => $this->department_id,
            ]);
        }

        Notification::make()
            ->title('Purchase Order has been created')
            ->success()
            ->send();

        $this->redirect(PurchaseOrdersResource::getUrl('index'));
    }
}
