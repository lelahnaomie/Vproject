<?php

use App\Http\Controllers\App\Dashboard\DashboardController;
use App\Http\Controllers\App\Dashboard\HrmDashboardController;
use App\Http\Controllers\App\Dashboard\AcademicDashboardController;
use App\Http\Controllers\App\Dashboard\HospitalDashboardController;
use App\Http\Controllers\App\Dashboard\EcommerceDashboardController;
use App\Http\Controllers\App\Dashboard\AttendanceDashboardController;
use App\Http\Controllers\App\Zk\ZkController;
use App\Http\Controllers\AttendanceController;

Route::group(['prefix' => 'dashboard'], function () {
    Route::view('/academy', 'dashboard.academy');
    Route::view('/ecommerce', 'dashboard.e-commerce');
    Route::view('/hrm', 'dashboard.hrm');
    Route::view('/hospital', 'dashboard.hospital');
    Route::view('/attendance', 'dashboard.attendance');
    Route::view('/ratings', 'dashboard.ratings');
    Route::view('/employees', 'dashboard.employee');

    Route::view('/department', 'dashboard.department');
    Route::view('/records', 'dashboard.record');

    Route::view('/working-hour', 'dashboard.workinghour');
});

// Attendance
// Route::get('default-attendance-info', [AttendanceController::class, 'showAttendanceDatatable']);
Route::get('default-attendance-info', [AttendanceDashboardController::class, 'defaultData']);
Route::get('lateness-overview', [AttendanceDashboardController::class, 'latenessOverview']);

// Route::get('audience-overview-chart', [DashboardController::class, 'audienceOverviewChart']);
// Route::get('browser-overview-chart', [DashboardController::class, 'browserOverviewChart']);


// Default dashboard
Route::get('default-dashboard-info', [DashboardController::class, 'getDefaultDashboardInfo']);
Route::get('audience-overview-chart', [DashboardController::class, 'audienceOverviewChart']);
Route::get('browser-overview-chart', [DashboardController::class, 'browserOverviewChart']);

// Academic Dashboard
Route::get('default-academic-info', [AcademicDashboardController::class, 'defaultData']);
Route::get('school-overview', [AcademicDashboardController::class, 'schoolOverview']);
Route::get('instructor-student-overview', [AcademicDashboardController::class, 'instructorStudentOverview']);
Route::get('available-course-list', [AcademicDashboardController::class, 'availableCourseList']);
Route::get('student-overview', [AcademicDashboardController::class, 'studentOverview']);

// E commerce Dashboard
Route::get('default-info', [EcommerceDashboardController::class, 'ecommerceDefaultInfo']);
Route::get('business-overview', [EcommerceDashboardController::class, 'businessOverview']);
Route::get('contact-overview', [EcommerceDashboardController::class, 'contactOverview']);
Route::get('team-overview', [EcommerceDashboardController::class, 'teamOverview']);

// Hospital Dashboard
Route::get('default-hospital-info', [HospitalDashboardController::class, 'defaultData']);
Route::get('hospital-activities', [HospitalDashboardController::class, 'hospitalActivities']);
Route::get('patient-statistics', [HospitalDashboardController::class, 'patientStatistics']);
Route::get('doctors-list', [HospitalDashboardController::class, 'doctorsList']);
Route::get('upcoming-appointments', [HospitalDashboardController::class, 'upComingAppointments']);

// Hrm Dashboard
Route::get('hrm-basic-data', [HrmDashboardController::class, 'basicData']);
Route::get('project-overview', [HrmDashboardController::class, 'projectOverview']);
Route::get('yearly-holiday', [HrmDashboardController::class, 'yearlyHoliday']);
Route::get('employee-overview', [HrmDashboardController::class, 'employeeOverview']);
// Route::get('zktrait/allData', [ZkController::class, 'allData']);

