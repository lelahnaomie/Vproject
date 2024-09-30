<?php

use App\Http\Controllers\App\Crud\CrudController;
use App\Http\Controllers\App\Crud\DepartmentCrudController;
use App\Http\Controllers\App\Crud\EmployeeCrudController;
use App\Http\Controllers\App\Crud\RecordCrudController;
use App\Http\Controllers\App\Crud\WorkingHourCrudController;
use App\Http\Controllers\App\DatatableController;
use App\Http\Controllers\App\Zk\ZkController;
use App\Models\Department;

Route::view('/datatable', 'tables.datatable');

Route::group(['prefix' => 'tables'], function () {
    Route::get('/basic-datatable', [DatatableController::class, 'showBasicDatatable'])
        ->name('basic-datatable.data');

    Route::get('/advance', [DatatableController::class, 'showAdvanceDatatable'])
        ->name('advance-datatable.data');

    // Route::get('/advance', [AttendanceController::class, 'showDatatable'])
    //      ->name('attendance-datatable.data');

    Route::get('/responsive', [DatatableController::class, 'showResponsiveDatatable'])
        ->name('responsive-datatable');

    Route::get('/filter', [DatatableController::class, 'datatableWithFilter'])
        ->name('filter-datatable');

    Route::get('/pagination', [DatatableController::class, 'datatablePagination'])
        ->name('paginate-datatable');

    Route::get('/functional', [CrudController::class, 'view'])
        ->name('functional');

    Route::get('/grid-view', [DatatableController::class, 'girdViewDataTable'])
        ->name('grid-view');
});

Route::resource('crud', CrudController::class);
Route::resource('department', DepartmentCrudController::class);
Route::resource('workinghour', WorkingHourCrudController::class);
Route::resource('record', RecordCrudController::class);
Route::resource('employee', EmployeeCrudController::class);


Route::get('/datatable/name', [CrudController::class, 'getNameFromDatatable']);

// Route::get('/employee', [ZkController::class, 'getemployeeFromDatatable']);

Route::get('/datatable/serverside/selectable-name', [CrudController::class, 'getSelectableName']);
Route::get('zktrait/getSelectableName', [ZkController::class, 'getSelectableName']);
// Route::get('zktrait/allData', [ZKController::class, 'allData']);


// Route::get('zk/getSerialNumber', [ZkController::class, 'getSerialNumber']);
// 'ZkController@getSerialNumber',



