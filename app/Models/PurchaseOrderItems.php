<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItems extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_order_id', 'product_id', 'quantity', 'price', 'total_price'];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrders::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
