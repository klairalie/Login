<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    protected $connection = 'capstone_central'; // ✅ Use the central DB
    protected $table = 'admin_activity_logs';

    protected $fillable = [
        'actor_email',
        'target_email',
        'module',
        'action',
        'changes',
        'ip_address',
    ];
}
