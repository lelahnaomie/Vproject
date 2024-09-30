<?php


namespace App\Filters\Core\traits;
namespace App\Traits;

use Carbon\Carbon;

trait AttendanceFilter
{
public function scopeToday($query, $column = 'datetime')
{
    return $query->whereDate($column, Carbon::today());
}

public function scopeYesterday($query, $column = 'datetime')
{
    return $query->whereDate($column, Carbon::yesterday());
}

public function scopeMonthToDate($query, $column = 'datetime')
{
    return $query->whereBetween($column, [Carbon::now()->startOfMonth(), Carbon::now()]);
}

public function scopeQuarterToDate($query, $column = 'datetime')
{
    $now = Carbon::now();
    return $query->whereBetween($column, [$now->startOfQuarter(), $now]);
}

public function scopeYearToDate($query, $column = 'datetime')
{
    return $query->whereBetween($column, [Carbon::now()->startOfYear(), Carbon::now()]);
}

public function scopeLast7Days($query, $column = 'datetime')
{
    return $query->whereBetween($column, [Carbon::today()->subDays(6), Carbon::now()]);
}

public function scopeLast30Days($query, $column = 'datetime')
{
    return $query->whereBetween($column, [Carbon::today()->subDays(29), Carbon::now()]);
}

public function scopeLastQuarter($query, $column = 'datetime')
{
    $now = Carbon::now();
    return $query->whereBetween($column, [$now->startOfQuarter()->subMonths(3), $now->startOfQuarter()]);
}

public function scopeLastYear($query, $column = 'datetime')
{
    return $query->whereBetween($column, [Carbon::now()->subYear(), Carbon::now()]);
}
}