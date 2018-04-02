<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Task[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function store(Request $request)
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
            return 'faile';
//            return Redirect::to('nerds/create')
//                ->withErrors($validator)
//                ->withInput(Input::except('password'));
        } else {
            // store
            $task = new Task;
            $task->name       = $request::get('name');
            $task->status     = 'created';
            $task->description = $request::get('description');
            $task->assignee = $request::get('assignee');
            $task->assigner = $request::get('assigner');
            $task->save();

            // redirect
            $request->session()->flash('message', 'Successfully created nerd!');
            return redirect()->route('login');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Task::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
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
        } else {
            // store
            $task = Task::find($id);
            $task->name       = $request::get('name');
            $task->status     = 'modified';
            $task->description = $request::get('description');
            $task->assignee = $request::get('assignee');
            $task->assigner = $request::get('assigner');
            $task->save();

            // redirect
            $request->session()->flash('message', 'Successfully created nerd!');
            return redirect()->route('login');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
