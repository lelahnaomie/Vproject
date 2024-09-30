<?php


namespace App\Filters\App\Traits;


trait AttendanceSearchFilter
{
    public function search($search)
    {
        $this->singleSearch($search, 'employee_id');
    }
}
