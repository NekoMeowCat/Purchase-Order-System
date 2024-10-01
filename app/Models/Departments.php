<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'user_in_charge_id',
    ];


    public function userInCharge()
    {
        return $this->belongsTo(User::class, 'user_in_charge_id');
    }
}
