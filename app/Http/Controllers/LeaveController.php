<?php

namespace App\Http\Controllers;

use App\Leave;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
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
            'typetype'   => 'required|string|max:255',
            'status'   => 'required|string|max:255',
            'leaver_id'   => 'required|integer',
            'substitution_id'   => 'nullable'
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return 'faile';
//            return Redirect::to('nerds/create')
//                ->withErrors($validator)
//                ->withInput(Input::except('password'));
        }
        // store
        $leave = new Leave();
        $leave->start = $request::get('start');
        $leave->end = $request::get('end');
        $leave->typetype = $request::get('typetype');
        $leave->status = $request::get('status');
        $leave->leaver_id = $request::get('leaver_id');
        $leave->substitution_id = $request::get('substitution_id');
        $leave->save();

        // redirect
        $request->session()->flash('message', 'Successfully created nerd!');
        return redirect()->route('login');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show(Leave $id)
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
            'name'       => 'required',
            'assignee'   => 'required',
            'assigner'   => 'required',
            'description'   => 'nullable',
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
        $leave->name       = $request::get('name');
        $leave->status     = 'modified';
        $leave->description = $request::get('description');
        $leave->assignee = $request::get('assignee');
        $leave->assigner = $request::get('assigner');
        $leave->save();

        // redirect
        $request->session()->flash('message', 'Successfully created nerd!');
        return redirect()->route('login');
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
