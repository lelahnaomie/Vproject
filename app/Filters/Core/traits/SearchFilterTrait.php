<?php


namespace App\Filters\Core\traits;


trait SearchFilterTrait
{
    public function search($search = null)
    {
        $this->name($search);
    }
    public function Asearch($search = null)
    {
        $this->employee_id($search);
    }
}
