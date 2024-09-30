<?php

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
use App\Models\Core\Status;
use App\Notifications\Core\User\UserNotification;
use Exception;
use Illuminate\Http\Request;

class EmployeeCrudController extends Controller
{
    public function __construct(UserService $service, UserFilter $filter)
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
        return (new AppUserFilter(
            $this->service
                ->filters($this->filter)
                ->select(['id', 'first_name', 'last_name', 'email', 'attendance_id', 'department_id', 'tel_number', 'created_by', 'status_id', 'created_at'])
                ->with('roles:id,name,is_default,type_id', 'status', 'profilePicture', 'department', 'workinghour')
                ->latest()
        ))->filter()
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
        // return $request;
        $request["password"] = "password";
        $request["role"] = "Employee";
        $this->service
            ->create()
            ->when($request->get('roles'), function (UserService $service) use ($request) {
                $service->assignRole($request->get('roles'));
            })
            ->when($request->get('workinghour_id'), function (UserService $service) use ($request) {
                $service->workingHour()->attach($request->get('workinghour_id'));
            })
            ->notify('user_created');

        return created_responses('data');
    }

    //**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
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
    public function update(Request $request, $id)
    {
            $user = $this->service->find($id);
            $email = ($request->input('email'));
            if ($user->email != $request->input('email')) {
                $existingemail = User::where('email', $email)->first();
                if ($existingemail) {
                    return array_merge([
                        'status' => false,
                        'message' => trans('email already exist', [
                            'name' => __t("user")
                        ])
                    ], []);
                }
            }
            $status = Status::findByNameAndType('status_active', 'user')->id;
            $request['status_id'] = $status;
            $this->service->where('id', $id)->update(\request()->only(
                'first_name',
                'email',
                'last_name',
                'workinghour_id',
                'attendance_id',
                'department_id',
                'tel_number','status_id'));
            return updated_responses('user');
        }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user = $this->service->find($id);
        if ($user->delete()) {
            return deleted_responses('user');
        }
        return failed_responses();
    }
}
