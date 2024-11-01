<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItems extends Model
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
        'date_required'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrders::class, 'prs_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }
}
