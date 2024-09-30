<?php


namespace App\Services\App\Crud;

use App\Models\Attendance;
use App\Services\App\AppService;

class AttendanceService extends AppService
{
    public function __construct(Attendance $attendance)
    {
        $this->model = $attendance;
    }

    /**
     * Get only name from Crud model
     * @return \Illuminate\Support\Collection
     */
    public function getName()
    {
        return $this->model->select('name')->get();
    }

    /**
     * Update Crud service
     * @param Crud $crud
     * @return Crud
     */
    public function update( Attendance $attendance)
    {
        $attendance->fill(request()->all());

        $this->model =  $attendance;

        $attendance->save();

        return  $attendance;
    }

    /**
     * Delete Crud service
     * @param Crud $crud
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Attendance $attendance)
    {
        return $attendance->delete();
    }
}
