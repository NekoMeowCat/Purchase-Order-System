<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use EightyNine\Approvals\Models\ApprovableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItems extends ApprovableModel
{
    use HasFactory;

    protected $fillable = [
        'prs_id',
        'supplier_id',
        'po_number',
        'status',
        'description',
        'po_date',
        'product',
        'quantity',
        'is_edited',
        'price',
        'amount',
        'total_amount',
        'date_required',
        'signature_finance',
        'signature_vpasa',
        'signature_pmo',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrders::class, 'prs_id', 'id');
    }
    public function department()
    {
        return $this->belongsTo(Departments::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'prs_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }
}
