<?php

namespace App\Http\Controllers\App\Crud;

use App\Filters\App\Crud\CrudFilter;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Notifications\App\Crud\CrudNotification;
use App\Services\App\Crud\DepartmentCrudService;
use Illuminate\Http\Request;

class DepartmentCrudController extends Controller
{  
    public function __construct(DepartmentCrudService $service, CrudFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->service
        ->filters($this->filter)
        ->latest()
        ->paginate(request()->get('per_page', 10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $crud = $this->service->save();

        notify()
            ->on('row_created')
            ->with($crud)
            ->send(CrudNotification::class);

        return created_responses('data');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->service->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $crud = $this->service->update($department);

        notify()
            ->on('row_updated')
            ->with($crud)
            ->send(CrudNotification::class);

        return updated_responses('data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        if ($this->service->delete($department)) {

            notify()
                ->on('row_deleted')
                ->with((object)$department->toArray())
                ->send(CrudNotification::class);

            return deleted_responses('data');
        }
        return failed_responses();
    }
}
