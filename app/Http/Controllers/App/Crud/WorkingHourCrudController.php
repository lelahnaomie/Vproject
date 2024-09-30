<?php

namespace App\Http\Controllers\App\Crud;

use App\Filters\App\Crud\CrudFilter;
use App\Http\Controllers\Controller;
use App\Models\WorkingHour;
use App\Notifications\App\Crud\CrudNotification;
use App\Services\App\Crud\WorkingHourCrudService;
use Illuminate\Http\Request;

class WorkingHourCrudController extends Controller
{
    public function __construct(WorkingHourCrudService $service, CrudFilter $filter)
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
    public function update(Request $request,  WorkingHour $workingHour)
    {
        $workingHour = $this->service->update($workingHour);

        notify()
            ->on('row_updated')
            ->with($workingHour)
            ->send(CrudNotification::class);

        return updated_responses('data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkingHour $workingHour)
    {
        if ($this->service->delete($workingHour)) {

            notify()
                ->on('row_deleted')
                ->with((object)$workingHour->toArray())
                ->send(CrudNotification::class);

            return deleted_responses('data');
        }
        return failed_responses();
    }
}
