<?php

namespace App\Models\Core\Auth\Traits\Relationship;

use App\Models\App\Chat\Message;
use App\Models\App\User\SocialLink;
use App\Models\Core\Auth\PasswordHistory;
use App\Models\Core\Auth\Profile;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\SocialAccount;
use App\Models\Core\Auth\User;
use App\Models\Core\Builder\Form\CustomFieldValue;
use App\Models\Core\File\File;
use App\Models\Core\Setting\Setting;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\StatusRelationship;

/**
 * Class UserRelationship.
 */
trait RecordRelationship
{
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','attendance_id');
    }
}
