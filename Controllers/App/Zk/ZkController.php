<?php

namespace App\Http\Controllers\App\Zk;

use App\Http\Controllers\Controller;
use App\Exceptions;
use zktrait;
use App\Helpers\App\ZKLibrary;
use App\Models\App\Zk\Zk;
use Carbon\Carbon;

class ZkController extends Controller{

 public function att(){
    $zk = new ZKLibrary('192.168.100.70', 4370, 'UDP');
    $zk->connect();
    $zk->disableDevice();
    $attendance = $zk->getAttendance("2024-09-24 00:00:00","2024-09-24 23:59:00");
    return response()->json(['data' => $attendance]);
 }

 public function store(){
    $zk = new ZKLibrary('192.168.100.70', 4370, 'UDP');
    $zk->connect();
    $zk->disableDevice();
    $attendance = $zk->getAttendance("2024-08-01 00:00:00","2024-08-31 23:59:00");
    Zk::create($attendance);
 }

//  public function todayData(){
//     $zk = new ZKLibrary('192.168.100.70', 4370, 'UDP');
//     $zk->connect();
//     $zk->disableDevice();
//     $attendance = $zk->getAttendance("2024-09-01 00:00:00", "2024-09-01 23:59:00");
//     $today = now()->toDateString();
//     $todayAttendance = array_filter($attendance, function($record) use ($today) {
//         return isset($record['datetime']) && 
//                \Carbon\Carbon::parse($record['datetime'])->toDateString() === $today;
//     });
//     return response()->json(array_values($todayAttendance));
//  }

 }