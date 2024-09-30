<?php


namespace App\Services\App\Crud;

use App\Models\Record;
use App\Services\App\AppService;

class ZkEcoUserService extends AppService
{
    public function __construct(Record $record)
    {
        $this->model = $record;

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
    public function update(Record $record)
    {
        $record->fill(request()->all());

        $this->model =  $record;

        $record->save();

        return  $record;
    }

    /**
     * Delete Crud service
     * @param Crud $crud
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Record  $record)
    {
        return $record->delete();
    }
}
