<?php

namespace App\Models;

use App\Models\Core\Auth\Traits\Relationship\RecordRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory,RecordRelationship;
    protected $table = "records";
    protected $fillable = ['name','user_id','password','role','created_at','updated_at'];
}
