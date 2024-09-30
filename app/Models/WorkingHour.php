<?php

namespace App\Models;

use App\Models\App\AppModel;
use App\Models\Core\Auth\Traits\Relationship\WorkingHourRelationship;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WorkingHour extends AppModel
{
    use HasFactory,
    WorkingHourRelationship;
    protected $table = "workinghours";
    protected $fillable = ['end','start'];
    protected function start(): Attribute
    {
        return Attribute::make(
            // get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => Carbon::createFromFormat('H:i', $value),
        );
    }
    protected function end(): Attribute
    {
        return Attribute::make(
            // get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => Carbon::createFromFormat('H:i', $value),
        );
    }

    public function getFullTimeAttribute()
    {
        return $this->start.'-'.$this->end;
    }
    protected $appends = [
        'full_time',
    ];


}
