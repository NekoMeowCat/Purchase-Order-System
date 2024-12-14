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
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class CustomCreatePO extends Page implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;

    protected ?string $heading = 'Purchase Request';
    protected static ?string $breadcrumb = "PRS";
    protected static ?string $title = 'Purchase Request';
    protected static string $resource = PurchaseOrdersResource::class;
    protected static string $view = 'filament.resources.purchase-orders-resource.pages.custom-create-p-o';

    public $rows = [];
    public $over_all_total = 0;
    public $pr_number;
    public $date_required;
    public $suppliers;
    public $departments;
    public $department;
    public $supplier_id;
    public $budget_code;
    public $purpose;
    public $payee;
    public $canvass_form;

    public function mount(): void
    {
        $this->suppliers = Suppliers::all();
        $this->departments = Departments::all();

        $this->rows = [
            [
                'quantity' => '',
                'unit_no' => '',
                'description' => '',
                'amount' => '',
                'prs_date' => '',
                'total' => '',
                'budget_code' => '',
                'date_required' => '',
            ]
        ];

        $this->pr_number = $this->generatePoNumber();
    }

    private function generatePoNumber(): string
    {
        $monthAbbr = strtoupper(date('M'));
        $year = date('y');
        $nextSequence = $this->getNextSequenceNumber();
        $sequence = str_pad($nextSequence, 5, '0', STR_PAD_LEFT);

        return $monthAbbr . $year . $sequence;
    }

    private function getNextSequenceNumber(): int
    {
        $monthAbbr = strtoupper(date('M'));
        $year = date('y');

        $lastPoNumber = PurchaseOrders::where('pr_number', 'like', "{$monthAbbr}{$year}%")
            ->orderBy('created_at', 'desc')
            ->first()
            ?->pr_number;

        return $lastPoNumber ? (int)substr($lastPoNumber, -5) + 1 : 1;
    }

    public function save()
    {
        $prs_date = Carbon::now()->format('Y-m-d');
        $data = $this->rows;
        // $department = auth()->user()->department->name;

        // dd($department);
        $over_all_total = collect($data)->sum('total');

        // Handle file upload
        if ($this->canvass_form) {
            try {
                $filename = $this->pr_number . '_' . time() . '.' . $this->canvass_form->getClientOriginalExtension();
                $path = $this->canvass_form->storeAs('canvass_forms', $filename, 'public');
                
                // Add notification for successful upload
                Notification::make()
                    ->title('File uploaded successfully')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                // Add error notification
                Notification::make()
                    ->title('File upload failed')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
                return;
            }
        } else {
            // Debug notification
            Notification::make()
                ->title('No file detected')
                ->warning()
                ->send();
        }

        foreach ($data as $row) {
            PurchaseOrders::create([
                'quantity' => $row['quantity'],
                'unit_no' => $row['unit_no'],
                'description' => $row['description'],
                'amount' => $row['amount'],
                'total' => $row['total'],
                'over_all_total' => $over_all_total,
                'prs_date' => Carbon::today()->toDateString(),
                'pr_number' => $this->pr_number,
                'budget_code' => $this->budget_code,
                'purpose' => $this->purpose,
                'payee' => $this->payee,
                'department' => auth()->user()->department->name,
                'date_required' => $row['date_required'],
                'user_id' => auth()->id(),
                'canvass_form' => $path ?? null,
            ]);
        }

        Notification::make()
            ->title('Purchase Order has been created')
            ->success()
            ->send();

        $this->redirect(PurchaseOrdersResource::getUrl('index'));
    }
}
