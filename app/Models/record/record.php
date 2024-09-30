<?php

namespace App\Models\record;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class record extends Model
{
    use HasFactory;
    protected $table = "records";
    protected $fillable = ['name','user_id','password','role'];
}
