<?php

namespace App\Http\Controllers;

use App\Leave;
use App\Task;
use GuzzleHttp;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function __construct()
    {
//        $this->authorizeResource(Task::class);
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Task[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Leave::all();
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
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function store(Request $request)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'start' => 'required|date',
            'end'   => 'required|date',
            'type'   => 'required|string|max:255',
            'note' => 'nullable|string',
            'status'   => 'nullable|string|max:255',
            'leaver_id'   => 'required|integer',
            'substitution_id'   => 'required|integer'
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return response()->json(['message' =>'form not valid','error'=>$validator->errors()]);
//            return 'faile';
//            return Redirect::to('nerds/create')
//                ->withErrors($validator)
//                ->withInput(Input::except('password'));
        }
//        $allLeavve = true;

        if($request->input('substitution_id')){
            $substritude = User::find($request->input('substitution_id'));
//            return $substritude->leaves();
            foreach ($substritude->leaves() as $sub_leave){
                if (strtotime($sub_leave->start) <= strtotime($request->input('start')) &&
                    strtotime($sub_leave->end) >= strtotime($request->input('emd'))
                ){
                    return response()->json(['message' =>'substitute already have task this day']);
//                    return response()->json([
//                        $sub_leave->start,
//                        $request->input('start'),
//                        $sub_leave->end,
//                        $request->input('end')
//                    ]);
                }
//                return response()->json([
//                    $sub_leave->start,
//                    $request->input('start'),
//                    $sub_leave->end,
//                    $request->input('end')
//                ]);
//                return response()->json([
//                    strtotime($sub_leave->start),
//                    strtotime($request->input('start')),
//                    strtotime($sub_leave->end),
//                    strtotime($request->input('end'))
//                ]);
            }
//            return 'fail';
//            return $substritude;
        }
        // store
        $leave = new Leave();
        $leave->start = $request->input('start');
        $leave->end = $request->input('end');
        $leave->type = $request->input('type');
//        $leave->status = $request->input('status');
        $leave->status = "pending";
        $leave->note = $request->input('note');
//        $leave->leaver_id = $request->input('leaver_id');
        $leave->leaver_id = $request->user()->id;
        $leave->substitution_id = $request->input('substitution_id');
        $leave->task_id = $request->input('task_id');
        $leave->save();
        $task = Task::find($leave->task_id );
//        return $task;
        $lineId = DB::table('lineUser')
            ->select(DB::raw('line_id'))
            ->where('user_id', '=',$task->assigner)
            ->get();
        $leaverLinId = DB::table('lineUser')
            ->select(DB::raw('line_id'))
            ->where('user_id', '=',$request->user()->id)
            ->get();

        $http = new GuzzleHttp\Client;
        $header = $request->header('Authorization');
        if(count($leaverLinId)<=0 || count($lineId)<=0){
            return response()->json(['message' => 'Leave created Successful but not line ', 'data' => $leave]);
        }
//        return $leaverLinId[count($leaverLinId)-1]->line_id;
        $response = $http->post('http://128.199.88.139:22212/notification', [
            'form_params' => [
                'start_date' => $request->input('start'),
                'end_date' => $request->input('end'),
                'line_id' =>  $lineId[count($lineId)-1]->line_id,
                'task_id' => $request->input('task_id'),
                'leave_id' => $leave->id,
                'leaver_name' => $request->user()->fname . ' ' . $request->user()->lname,
                'authorization' => $header,
                'line_sub'=> $leaverLinId[count($leaverLinId)-1]->line_id
            ],
            'http_errors' => false
        ]);
        return $response;
//        return $leave;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Leave::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function edit(Leave $leave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'start' => 'required|date',
            'end'   => 'required|date',
            'type'   => 'required|string|max:255',
            'note' => 'nullable|string',
            'status'   => 'required|string|max:255',
//            'leaver_id'   => 'required|integer',
            'substitution_id'   => 'nullable'
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return response()->json(['message' =>'form not valid','error'=>$validator->errors()]);
//            return Redirect::to('nerds/create')
//                ->withErrors($validator)
//                ->withInput(Input::except('password'));
        }
        // store
        $leave = Leave::find($id);
        $leave->start = $request->input('start');
        $leave->end = $request->input('end');
        $leave->type = $request->input('type');
        $leave->status = $request->input('status');
        $leave->note = $request->input('note');
//        $leave->leaver_id = $request->input('leaver_id');
        $leave->leaver_id = $request->user()->id;
        $leave->substitution_id = $request->input('substitution_id');
        $leave->task_id = $request->input('task_id');
        $leave->save();

        return response()->json(['message' =>'successful','data'=>$leave]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete
        $leave = Leave::find($id);
        $leave->delete();

        // redirect

        return response()->json('message', 'Successfully deleted the nerd!');
    }

    public function substituteApprove(Request $request, $id){
        try {
            $leave = Leave::findOrFail($id);
            $leave->status = 'substituteApproved';
            $leave->save();
            return response()->json(['message'=> 'Successfully approve leave.']);
        }
        catch(ModelNotFoundException $e)
        {
            return $e;
        }
    }
    public function substituteDeny(Request $request, $id){
        try {
            $leave = Leave::findOrFail($id);
            if($request->user()->id != $leave->substitution){
                return response()->json(['message' =>'not valid substitution','error'=>'no permission'],400);
            }
            $leave->status = 'substituteDenied';
            $leave->save();
            return response()->json(['message'=> 'Successfully approve leave.']);
        }
        catch(ModelNotFoundException $e)
        {
            return $e;
        }
    }

    /**
     * Determine whether the user can delete the leave.
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function approve(Request $request, $id)
    {
        try {
            $leave = Leave::findOrFail($id);
            if($request->user()->role == 'Subordinate'){
                if($request->user()->id != $leave->substitution_id){
                    return response()->json(['message' =>'not valid substitution','error'=>'no permission'],400);
                }
                $leave->status = 'substituteApproved';
                $leave->save();
                return response()->json(['message'=> 'Successfully approve leave.']);
            }
            if($request->user()->role == 'Supervisor'){
                if($leave->status != 'substituteApproved'){
                    return response()->json(['message' =>'wait for substitute to approve','error'=>'no permission'],400);
                }
                try{
                    $task = Task::findOrFail($leave->task_id);
                }
                catch(ModelNotFoundException $e)
                {
                    return response()->json(['message' =>'task not found','error'=>$e],400);
                }
                if($request->user()->id != $task->assigner){
                    return response()->json(['message' =>'not valid supervisor','error'=>'no permission'],400);
                }
                $leave->status = 'approved';
                $leave->save();
                return response()->json(['message'=> 'Successfully approve leave.']);
            }
            return  response()->json(['message'=> 'not thing to do'],400);
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['message' =>'leave not found','error'=>$e],400);
        }
    }

    /**
     * Determine whether the user can delete the leave.
     *
     * @param $id
     * @return mixed
     */
    public function deny($id)
    {
        try {
            $leave = Leave::findOrFail($id);
            if($request->user()->role == 'Subordinate'){
                if($request->user()->id != $leave->substitution_id){
                    return response()->json(['message' =>'not valid substitution','error'=>'no permission'],400);
                }
                $leave->status = 'substituteDenied';
                $leave->save();
                return response()->json(['message'=> 'Successfully approve leave.']);
            }
            if($request->user()->role == 'Supervisor'){
                if($leave->status != 'substituteApproved'){
                    return response()->json(['message' =>'wait for substitute to approve','error'=>'no permission'],400);
                }
                try{
                    $task = Task::findOrFail($leave->task_id);
                }
                catch(ModelNotFoundException $e)
                {
                    return response()->json(['message' =>'task not found','error'=>$e],400);
                }
                if($request->user()->id != $task->assigner){
                    return response()->json(['message' =>'not valid supervisor','error'=>'no permission'],400);
                }
                $leave->status = 'denied';
                $leave->save();
                return response()->json(['message'=> 'Successfully deny leave.']);
            }
            return  response()->json(['message'=> 'not thing to do'],400);
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['message' =>'leave not found','error'=>$e],400);
        }
    }
    public function pending (Request $request)
    {
        if ($request->user()->role == 'Supervisor')
        {
            $ids = array();
            foreach($request->user()->subordinates()->get() as $sub){
                array_push($ids, $sub->id);
            }
//            return $ids;
            return DB::table('leaves')
                ->select(DB::raw('*'))
                ->whereIn('leaver_id', $ids)
                ->where('status','=','pending')
                ->get();
        }

    }

    public function substitutable(Request $request, $task_id){
        try{
            $task = Task::findOrFail($task_id);
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['message' =>'task not found','error'=>$e],400);
        }
        try{
            $supervisor = User::findOrFail($task->assigner);
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['message' =>'task not found','error'=>$e],400);
        }
        $subordinates = $supervisor->subordinates;
        $free_sub = array();
        foreach ($subordinates as $sub){
            $free = false;
            foreach ($sub->tasks() as $sub_task) {
                if (strtotime($sub_task->start) < strtotime($task->start) &&
                    strtotime($sub_task->end) < strtotime($task->start) ||
                    strtotime($sub_task->start) > strtotime($task->end) &&
                    strtotime($sub_task->end) > strtotime($task->end)){
                    $free = true;
                    break;
                }
            }
            if(!$free){
                break;
            }
            foreach ($sub->leaves() as $sub_leave) {
                if (strtotime($sub_leave->start) < strtotime($task->start) &&
                    strtotime($sub_leave->end) < strtotime($task->start) ||
                    strtotime($sub_leave->start) > strtotime($task->end) &&
                    strtotime($sub_leave->end) > strtotime($task->end)){
                    $free = true;
                    break;
                }
            }
            if($free){
                array_push($free_sub, $sub);
            }
        }
        return $free_sub;
    }
}
