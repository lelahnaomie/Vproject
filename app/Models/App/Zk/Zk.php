<?php

namespace App\Models\App\Zk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zk extends Model
{
    use HasFactory;
    protected $table = "zk";
    protected $fillable = ['employee_id', 'datetime', 'checkin','name','updated_at','created_at'];

    // public function employeeIdScope($query, $employeeId)
    // {
    //     return $query->where('employee_id', $employeeId);
    // }
}
