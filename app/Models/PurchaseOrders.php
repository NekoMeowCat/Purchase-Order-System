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
        'total',
        'sub_total',
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
        return $this->hasMany(PurchaseOrderItems::class);
    }
}
