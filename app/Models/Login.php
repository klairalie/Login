<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Allows authentication features (if needed)
use Illuminate\Notifications\Notifiable;
use App\Models\Employeeprofiles;

class Login extends Authenticatable
{
    use HasFactory, Notifiable;

    // Table name
    protected $table = 'logins';

    // Primary key
    protected $primaryKey = 'user_id';

    // Mass assignable fields
    protected $fillable = [
        'position',
        'username',
        'password',
    ];

public $timestamps = true;

}
