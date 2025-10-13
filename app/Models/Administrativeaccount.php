<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Employeeprofiles;
class Administrativeaccount extends Authenticatable
{
    use HasFactory;
    
    protected $table = 'administrativeaccounts';
    protected $primaryKey = 'admin_id';
    protected $hidden = ['password'];

    protected $fillable = [
        'employeeprofiles_id',
        'username',
        'password',
        'admin_position'
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeProfiles::class, 'employeeprofiles_id', 'employeeprofiles_id');
    }
}
