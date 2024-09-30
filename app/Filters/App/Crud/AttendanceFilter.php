<?php


namespace App\Filters\App\Crud;

use App\Filters\App\Traits\AttendanceRangeFilter;
use App\Filters\App\Traits\AttendanceSearchFilter;
use App\Filters\App\Traits\DateRangeFilter;
use App\Filters\App\Traits\SearchFilter;
use App\Filters\App\Traits\StatusFilter;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class AttendanceFilter extends FilterBuilder
{
    use DateRangeFilter, AttendanceSearchFilter, StatusFilter;


   
    public function filterWithDate($date = null)
    {
        $this->whereClause('datetime', $date);
    }

    public function searchSelect($employee_id = null)
    {
        $this->whereClause('employee_id', $employee_id);
    }

    public function searchSelectable($search = null)
    {
        $this->singleSearch($search, 'employee_id');
    }

    public function serversideSelect($values = null)
    {
        $this->builder->when($values, function (Builder $builder) use ($values) {
            $builder->whereIn('id', array_values(explode(',', $values)));
        });
    }
}
