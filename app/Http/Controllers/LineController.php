<?php

namespace App\Http\Controllers;

use App\Traits\PassportToken;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class LineController extends Controller
{
    use PassportToken;
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

    public function addUser(Request $request, $id){
        $token = $this->getToken($id);
        if(!$token){
            return response()->json(['message' => 'cannot find user', 'error' => null]);
        }
        return response()->json([
            'token'=> json_decode($token->getBody(),true),
            'url'=> URL::to('/api/users'),
            'method' => 'post'
        ]);
    }

    public function addTask(Request $request, $id){
        $token = $this->getToken($id);
        if(!$token){
            return response()->json(['message' => 'cannot find user', 'error' => null]);
        }
        return response()->json([
            'token'=> json_decode($token->getBody(),true),
            'url'=> URL::to('/api/tasks'),
            'method' => 'post'
        ]);
    }
    public function listTask(Request $request, $id){
        $user = $this->getUser($id);
        if(!$user){
            return response()->json(['message' => 'cannot find user', 'error' => null]);
        }
        return response()->json($user->tasks());
    }
    public function listLeave(Request $request, $id){
        $user = $this->getUser($id);
        if(!$user){
            return response()->json(['message' => 'cannot find user', 'error' => null]);
        }
        return response()->json($user->leaves());
    }
    public function requestLeave(Request $request, $id){
        $token = $this->getToken($id);
        if(!$token){
            return response()->json(['message' => 'cannot find user', 'error' => null]);
        }
        return response()->json([
            'token'=> json_decode($token->getBody(),true),
            'url'=> URL::to('/api/leaves'),
            'method' => 'post'
        ]);
    }
    private function getUser($id){
        $userId = DB::table('lineUser')
            ->select(DB::raw('user_id'))
            ->where('line_id', '=', $id)
            ->get();
        //        chmod storage/660 oauth-p*
        //        chown -R root:www-data oauth-p*
        if(count($userId)<=0){
            return null;
        }
        try{
            return User::findOrFail($userId[count($userId)-1]->user_id);
        }
        catch(ModelNotFoundException $e)
        {
            return null;
        }
    }
    private function getToken($id){
        $user = $this->getuser($id);
        return $this->getBearerTokenByUser($user, 1, true);
    }
}
