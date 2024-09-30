<?php
namespace App\Http\Controllers\App\Crud;


namespace App\Http\Controllers\App\Crud;

use App\Filters\App\Crud\CrudFilter;
use App\Filters\Core\UserFilter;
use App\Http\Controllers\Controller;
use App\Jobs\User\UserDeleted;
use App\Filters\Common\Auth\UserFilter as AppUserFilter;
use App\Notifications\App\Crud\CrudNotification;
use App\Services\App\Crud\CrudService;
use App\Services\Core\Auth\UserService;
use App\Models\Core\Auth\User;
use App\Http\Requests\Core\Auth\User\UserRequest;
use App\Notifications\Core\User\UserNotification;
use App\Services\App\Crud\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(AttendanceService $service, CrudFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }
    public function store(Request $request)
    {
        $crud = $this->service->save();

        notify()
            ->on('row_created')
            ->with($crud)
            ->send(CrudNotification::class);

        return created_responses('data');
    }

}
