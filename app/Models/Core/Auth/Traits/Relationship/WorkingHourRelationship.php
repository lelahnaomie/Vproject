<?php

namespace App\Models\Core\Auth\Traits\Relationship;

use App\Models\Core\Auth\User;


/**
 * Class UserRelationship.
 */
trait WorkingHourRelationship
{
    public function user()
    {
        return $this->belongsToMany(User::class,'user_workinghour','workionghour_id','user_id');
    }
}
