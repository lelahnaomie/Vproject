<?php

namespace App\Http\Controllers\App\Zk;

use App\Http\Controllers\Controller;
use App\Exceptions;
use zktrait;
use App\Helpers\App\ZKLibrary;
use App\Models\App\Zk\Zk;
use App\Models\Attendance;
use App\Models\Core\Auth\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZkController extends Controller
{

   public function att()
   {
      $zk = new ZKLibrary('192.168.100.70', 4370, 'UDP');
      $zk->connect();
      $zk->disableDevice();
      $attendance = $zk->getAttendance("2024-09-24 00:00:00", "2024-09-24 23:59:00");
      return response()->json(['data' => $attendance]);
   }

   public function store()
   {
      try {
         $zk = new ZKLibrary('192.168.100.70', 4370, 'UDP');
         $zk->connect();
         $zk->disableDevice();

         $attendanceRecords = $zk->getAttendance();
         $createdRecords = [];

         foreach ($attendanceRecords as $record) {
            $createdRecords[] = Zk::create($record);
         }

         return response()->json(['data' => $createdRecords]);
      } catch (\Exception $e) {
         return response()->json(['error' => $e->getMessage()], 500);
      } finally {
         $zk->enableDevice();
      }
   }

   // public function todayData()
   // {
   //    $zk = new ZKLibrary('192.168.100.70', 4370, 'UDP');
   //    $zk->connect();
   //    $zk->disableDevice();
   //    $todayStart = Carbon::now()->startOfDay()->toDateTimeString();
   //    $todayEnd = Carbon::now()->endOfDay()->toDateTimeString();
   //    $attendance = $zk->getAttendance($todayStart, $todayEnd);
   //    $todayAttendance = array_filter($attendance, function ($record) {
   //       return isset($record['datetime']);
   //    });
   //    return response()->json(['data' => $todayAttendance]);
   // }

   // public function monthlyData(){
   //    $zk = new ZKLibrary('192.168.100.70', 4370, 'UDP');
   //    $zk->connect();
   //    $zk->disableDevice();
   //    $monthStart = Carbon::now()->startOfMonth()->toDateTimeString();
   //    $monthEnd = Carbon::now()->endOfMonth()->toDateTimeString();
   //    $attendance = $zk->getAttendance($monthStart, $monthEnd);
   //    foreach($attendance as $attendance){
   //       $zk = Attendance::create($attendance);
   //  }
   //    $monthlyAttendance = array_filter($attendance, function($record) {
   //        return isset($record['datetime']);
   //    });
   //    return response()->json(['data' => $monthlyAttendance]);
   // }
   public function allData(Request $request)
   {
      $employeeId = $request->input('employee_id');
      $search = $request->input('search');
      $dateRange = $request->input('date');
      $specificDate = $request->input('specificDate');

      $query = DB::table('zk');



      if ($dateRange) {
         $dateRange = json_decode($dateRange, true);
         if (isset($dateRange['start']) && isset($dateRange['end'])) {
            $start = Carbon::parse($dateRange['start'])->startOfDay();
            $end = Carbon::parse($dateRange['end'])->endOfDay();
            $query->whereBetween('datetime', [$start, $end]);
         }
      }


      if ($specificDate) {

         $parsedDate = Carbon::parse($specificDate)->toDateString();
         $query->whereDate('datetime', $parsedDate);
      }

      if ($employeeId) {
         $query->where('employee_id', 'like', "%" . $employeeId . "%");
      }

      if ($search) {
         $query->where('employee_id', 'like', "%" . $search . "%");
      }

      $data = $query->orderBy('datetime', 'desc')->paginate(10);

      if ($data->isEmpty()) {
         return response()->json(['message' => 'Data not found']);
      }

      return response()->json($data);
   }

   public function Lateness(Request $request)
   {
       $employeeId = $request->input('employee_id');
       $entryTime = Carbon::parse($request->input('starttime')); 
   
       
       $employee = User::with('workingHour')->where('employee_id', $employeeId)->first();
   
       if (!$employee) {
           return response()->json(['message' => 'Employee not found'], 404);
       }
   
       $startTime = Carbon::parse($employee->workingHour->start_time);
       $gracePeriodEndTime = $startTime->addMinutes(10);
   
       $isLate = $entryTime->isAfter($gracePeriodEndTime);
   
       Attendance::create([
           'employee_id' => $employeeId,
           'clock_in_time' => $entryTime,
           'late' => $isLate,
       ]);
   
       return response()->json(['message' => 'Attendance recorded successfully.']);
   }
}
