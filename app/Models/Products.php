<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'supplier_id'];

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItems::class);
    }
}
