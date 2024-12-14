<?php

namespace App\Filament\Resources\PurchaseOrderItemsResource\Pages;

use App\Filament\Resources\PurchaseOrderItemsResource;
use Filament\Resources\Pages\EditRecord;
use App\Models\PurchaseOrderItems;
use App\Models\Suppliers;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class EditPO extends EditRecord
{
    protected static string $resource = PurchaseOrderItemsResource::class;
    protected static string $view = 'filament.resources.purchase-order-items.edit-po';

    public $pr_number;
    public $po_number;
    public $supplier_id;
    public $suppliers;
    public $currentDate;
    public $date_required;
    public $terms;
    public $rows = [];
    public $total_amount = 0;
    public $status;

    public function mount($record): void
    {
        parent::mount($record);
        $this->pr_number = $this->record->purchaseOrder->pr_number ?? 'No PR Number';
        $this->po_number = $this->record->po_number ?? $this->generatePoNumber();
        $this->suppliers = Suppliers::all()->pluck('name', 'id');
        $this->supplier_id = $this->record->supplier_id ?? null;
        $this->currentDate = Carbon::now()->format('Y-m-d');
        $this->date_required = $this->record->date_required;
        $this->status = $this->record->status;

        // Initialize rows with existing items or one empty row
        if ($this->record->exists) {
            $this->rows = $this->record->where('po_number', $this->po_number)
                ->get()
                ->map(function ($item) {
                    return [
                        'quantity' => $item->quantity,
                        'description' => $item->description,
                        'price' => $item->price,
                        'amount' => $item->amount,
                    ];
                })->toArray();
        }

        if (empty($this->rows)) {
            $this->rows = [[
                'quantity' => '',
                'description' => '',
                'price' => '',
                'amount' => '',
            ]];
        }

        $this->calculateTotalAmount();
    }

    public function addRow()
    {
        $this->rows[] = [
            'quantity' => '',
            'description' => '',
            'price' => '',
            'amount' => '',
        ];
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
        $this->calculateTotalAmount();
    }

    public function updateAmount($index)
    {
        $quantity = floatval($this->rows[$index]['quantity'] ?? 0);
        $price = floatval($this->rows[$index]['price'] ?? 0);
        $this->rows[$index]['amount'] = number_format($quantity * $price, 2, '.', '');
        $this->calculateTotalAmount();
    }

    public function calculateTotalAmount()
    {
        $this->total_amount = array_reduce($this->rows, function ($carry, $row) {
            return $carry + floatval($row['amount'] ?? 0);
        }, 0);
    }

    private function generatePoNumber(): string
    {
        $month = date('m');
        $day = date('d');
        $year = date('y');
        $prefix = "PO{$month}{$day}{$year}";
        $lastPoNumber = PurchaseOrderItems::where('po_number', 'like', "{$prefix}%")
            ->latest('po_number')
            ->value('po_number');
        $nextSequence = $lastPoNumber ? (int)substr($lastPoNumber, -3) + 1 : 1;
        $sequence = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
        return "{$prefix}{$sequence}";
    }

    public function submit()
    {
        $this->validate([
            'supplier_id' => 'required',
            'date_required' => 'required',
            'rows' => 'required|array|min:1',
            'rows.*.quantity' => 'required|numeric',
            'rows.*.description' => 'required',
            'rows.*.price' => 'required|numeric',
            'rows.*.amount' => 'required|numeric',
        ]);

        // Begin transaction
        DB::beginTransaction();

        try {
            // Update the current record
            $this->record->update([
                'prs_id' => $this->record->purchaseOrder->id,
                'supplier_id' => $this->supplier_id,
                'po_number' => $this->po_number,
                'po_date' => $this->currentDate,
                'quantity' => $this->rows[0]['quantity'],
                'description' => $this->rows[0]['description'],
                'price' => $this->rows[0]['price'],
                'amount' => $this->rows[0]['amount'],
                'total_amount' => $this->total_amount,
                'date_required' => $this->date_required,
                'is_edited' => 1,
                'status' => $this->status,
            ]);

            DB::commit();
            Notification::make()
                ->title('Purchase Order has been updated')
                ->success()
                ->send();

            $this->redirect(PurchaseOrderItemsResource::getUrl('index'));
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error saving Purchase Order')
                ->danger()
                ->send();
        }
    }
}
