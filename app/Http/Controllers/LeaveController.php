<?php

namespace App\Http\Controllers;

use App\Leave;
use App\Task;
use Illuminate\Http\Request;
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
     * @return \Illuminate\Http\Response
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
            'status'   => 'required|string|max:255',
            'leaver_id'   => 'required|integer',
            'substitution_id'   => 'nullable'
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
        // store
        $leave = new Leave();
        $leave->start = $request->input('start');
        $leave->end = $request->input('end');
        $leave->type = $request->input('type');
        $leave->status = $request->input('status');
        $leave->note = $request->input('note');
        $leave->leaver_id = $request->input('leaver_id');
        $leave->substitution_id = $request->input('substitution_id');
        $leave->task_id = $request->input('task_id');
        $leave->save();

        return $leave;

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
        return $request->input('start');
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'start' => 'required|date',
            'end'   => 'required|date',
            'type'   => 'required|string|max:255',
            'note' => 'nullable|string',
            'status'   => 'required|string|max:255',
            'leaver_id'   => 'required|integer',
            'substitution_id'   => 'nullable'
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return 'fail';
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
        $leave->leaver_id = $request->input('leaver_id');
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

    /**
     * Determine whether the user can delete the leave.
     *
     * @param $id
     * @return mixed
     */
    public function approve($id)
    {
        try {
            $leave = Leave::findOrFail($id);
            $leave->status = 'approve';
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
     * @param $id
     * @return mixed
     */
    public function deny($id)
    {
        try{
            $leave = Leave::findOrFail($id);
            $leave->status = 'deny';
            $leave->save();
            return response()->json(['message'=>'Successfully deny leave.']);
        }
        catch(ModelNotFoundException $e)
        {
            return $e;
        }
    }
}
