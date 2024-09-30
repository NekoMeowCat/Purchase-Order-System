<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EightyNine\Approvals\Models\ApprovableModel;


class PurchaseOrders extends ApprovableModel
{
    use HasFactory;

    protected $fillable = [
        'item_no',
        'name',
        'department',
        'description',
        'unit_price',
        'quantity',
        'total',
        'sub_total',
        'po_number',
        'tax',
        'over_all_total',
        'po_date',
        'comment',
        'rejected_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItems::class, 'purchase_order_id');
    }

    public function processApprovalFlowSteps()
    {
        return $this->hasMany(ProcessApprovalFlowStep::class, 'process_approval_flow_id', 'id');
    }
}
