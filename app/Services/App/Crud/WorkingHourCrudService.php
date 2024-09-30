<?php


namespace App\Services\App\Crud;

use App\Models\WorkingHour;
use App\Services\App\AppService;

class WorkingHourCrudService extends AppService
{
    public function __construct(WorkingHour $workingHour)
    {
        $this->model = $workingHour;
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
    public function update(WorkingHour $workingHour)
    {
        $workingHour->fill(request()->all());

        $this->model =  $workingHour;

        $workingHour->save();

        return  $workingHour;
    }

    /**
     * Delete Crud service
     * @param Crud $crud
     * @return bool|null
     * @throws \Exception
     */
    public function delete(WorkingHour  $workingHour)
    {
        return $workingHour->delete();
    }
}
