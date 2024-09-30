<?php

namespace App\Helpers\App\Traits;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

trait DashboardHelper
{
    public function generateRandomValuesSumHundred($number_off_value)
    {
        $sum = 100;

        $array_of_sum_hundred = [];
        $element = 0;

        while (array_sum($array_of_sum_hundred) != $sum) {

            $array_of_sum_hundred[$element] = mt_rand(0, $sum / mt_rand(1, $number_off_value));

            if (++$element == $number_off_value) {
                $element = 0;
            }
        }

        return $array_of_sum_hundred;

    }

    public function getLatecomersByDepartment()
    {
        $employees = Employee::all();
        $attendances = Attendance::all();

        $departmentLatecomers = [];

        foreach ($attendances as $attendance) {
            $checkInTime =  \Carbon\Carbon::parse($attendance->datetime);
            if ($checkInTime->hour >= "10:00") {
                $employee = $employees->where('attendance_id', $attendance->id)->first();
                if ($employee) {
                    $department = $employee->department_id;
                    if (!isset($departmentLatecomers[$department])) {
                        $departmentLatecomers[$department] = 0;
                    }
                    $departmentLatecomers[$department]++;
                }
            }
        }

        return $departmentLatecomers;
    }


}