<?php


namespace App\Services\App\Crud;

use App\Models\Department;
use App\Services\App\AppService;

class DepartmentCrudService extends AppService
{
    public function __construct(Department $department)
    {
        $this->model = $department;
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
    public function update(Department $department)
    {
        $department->fill(request()->all());

        $this->model =  $department;

        $department->save();

        return  $department;
    }

    /**
     * Delete Crud service
     * @param Crud $crud
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Department  $department)
    {
        return $department->delete();
    }
}
