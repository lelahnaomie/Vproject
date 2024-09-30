<?php

namespace App\Http\Controllers\App\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\App\Dashboard\AttendanceDashboardService;
use Illuminate\Http\Request;

class AttendanceDashboardController extends Controller
{
    public function __construct(AttendanceDashboardService $service)
    {
        $this->service = $service;
    }

    public function defaultData()
    {
        return $this->service->defaultData();
    }

    public function studentOverview(Request $request)
    {
        return $this->service->studentOverview($request->key);
    }

    public function latenessOverview()
    {
        return $this->service->latenessOverview();
    }

    public function instructorStudentOverview()
    {
        return $this->service->instructorStudentOverview();
    }

    public function availableCourseList(Request $request)
    {
        return $this->service->availableCourseList($request);
    }
}
