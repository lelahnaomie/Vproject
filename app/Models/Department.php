<?php

namespace App\Models;

use App\Models\App\AppModel;
use App\Models\Core\Auth\Traits\Relationship\DepartmentRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends AppModel
{
    use HasFactory,DepartmentRelationship;
    protected $table = "departments";
    protected $fillable = ['name'];
}
