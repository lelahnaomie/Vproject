<?php


namespace App\Models;

use App\Models\App\AppModel;
use App\Models\Core\Auth\Traits\Relationship\DepartmentRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends AppModel
{
    use HasFactory;
    protected $table = "attendances";
    protected $fillable = ['employee_id','name', 'datetime', 'checkin'];
}
