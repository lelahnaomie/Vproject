<?php


namespace App\Filters\App\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait DateRangeFilter
{
    public function date($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);
        $this->builder->when($date, function (Builder $builder) use ($date){
            $builder->whereBetween(DB::raw('DATE(created_at)'), array_values($date));
        });
    }
}