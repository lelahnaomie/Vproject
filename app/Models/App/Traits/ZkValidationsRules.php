<?php


namespace App\Models\App\Traits;


trait ZkValidationRules
{
    public function createdRules()
    {
        return [
            'id' => 'required|min:1|max:3',
            'name' => 'required|min:2|max:195',
            'datetime' => 'required|datetime',
            'checkin' => 'required|checkin',
        ];
    }

    public function updatedRules()
    {
        return [
            'id' => 'required|min:1|max:3',
            'name' => 'required|min:2|max:195',
            'datetime' => 'required|datetime',
            'checkin' => 'required|checkin',
        ];
    }
}