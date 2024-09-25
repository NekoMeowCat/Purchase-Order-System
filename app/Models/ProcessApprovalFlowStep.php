<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessApprovalFlowStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'process_approval_flow_id',
        'role_id',
        'permissions',
        'order',
        'action',
        'active',
        'tenant_id'
    ];
}
