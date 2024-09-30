<?php

namespace App\Http\Controllers\Record;
use App\Http\Controllers\Controller;

use App\Exceptions;
use App\Filters\App\Crud\CrudFilter;
use recordtrait;
use App\Helpers\App\ZKLibrary;
use App\Models\Record;
use App\Notifications\App\Crud\CrudNotification;
use App\Services\App\Crud\ZkEcoUserService;
use AWS\CRT\HTTP\Request;
use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\DB;

class RecordController extends Controller
{
    
    
     public function __construct(ZkEcoUserService $service, CrudFilter $filter)
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
          ->select(['id','user_id', 'role', 'created_at'])
          ->with('user')
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

      public function user(){
        $zk = new ZKLibrary('192.168.100.70', 4370, 'UDP');
        $zk->connect();
        $zk->disableDevice();
        $user = $zk->getUser();

        
        return response()->json(['data' => $user]);
     }
     
     public function store(Request $request)
     {
        $zk = new ZKLibrary('192.168.100.70', 4370, 'UDP');
        $zk->connect();
        $zk->disableDevice();
        $user = $zk->getUser();
        foreach($user as $user){
            $record = Record::create($user);
        }
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
     public function update(Request $request, Record $record)
     {
         $crud = $this->service->update($record);
 
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
     public function destroy(Record $record)
     {
         if ($this->service->delete($record)) {
 
             notify()
                 ->on('row_deleted')
                 ->with((object)$record->toArray())
                 ->send(CrudNotification::class);
 
             return deleted_responses('data');
         }
         return failed_responses();
     }

}
