<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LineController extends Controller
{

    public function genOTPAdministrator(){
        $otp = file_get_contents('http://128.199.88.139:22211/administrator_otp');
        DB::insert('insert into lineUser (otp, user_id, line_id, role) values (?, ?, ?, ?)',
            [$otp, request()->user()->id,'','Administrator']);
        return response()->json(['opt'=>$otp]);
    }
    public function genOTPASupervisor(){
        $otp = file_get_contents('http://128.199.88.139:22212/supervisor_otp');
        DB::insert('insert into lineUser (otp, user_id, line_id, role) values (?, ?, ?, ?)',
            [$otp, request()->user()->id,'','Supervisor']);
        return response()->json(['opt'=>$otp]);
    }
    public function genOTPASubordinate(){
        $otp = file_get_contents('http://128.199.88.139:22213/subordinate_otp');
        DB::insert('insert into lineUser (otp, user_id, line_id, role) values (?, ?, ?, ?)',
            [$otp, request()->user()->id,'','Subordinate']);

        return response()->json(['opt'=>$otp]);
    }
    public function genOTP(){
        $port = 1;
        if (strtolower(request()->user()->role) == 'supervisor') {
            $port = 2;
        }
        if (strtolower(request()->user()->role) == 'subordinate') {
            $port = 3;
        }
        $otp = file_get_contents('http://128.199.88.139:2221'.$port.'/'.strtolower(request()->user()->role).'_otp');
        DB::insert('insert into lineUser (otp, user_id, line_id, role) values (?, ?, ?, ?)',
            [$otp, request()->user()->id,'',request()->user()->role]);
        return response()->json(['opt'=>$otp]);
    }
}
