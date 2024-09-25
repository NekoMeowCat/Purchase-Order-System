<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_order_id', 'user_id', 'approval_status', 'comments'];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrders::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
