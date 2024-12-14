<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EightyNine\Approvals\Models\ApprovableModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;



class PurchaseOrders extends ApprovableModel
{
    use HasFactory;

    protected $casts = [
        'attachments' => 'array',
    ];

    protected $fillable = [
        'unit_no',
        // 'department_id',
        'description',
        'unit_price',
        'supplier_id',
        'quantity',
        'total',
        'amount',
        'budget_code',
        'purpose',
        'payee',
        'pr_number',
        'over_all_total',
        'prs_date',
        'user_id',
        'date_required',
        'comment',
        'rejected_by',
        'head_signature',
        'signature_finance',
        'signature_vpasa',
        'signature_pmo',
        'department',
        'canvass_form',
    ];

    public function getFormattedDateRequiredAttribute()
    {
        return Carbon::parse($this->date_required)->format('F j, Y');
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItems::class, 'prs_id'); // Adjust 'prs_id' if necessary
    }

    public function processApprovalFlowSteps()
    {
        return $this->hasMany(ProcessApprovalFlowStep::class, 'process_approval_flow_id', 'id');
    }
}
