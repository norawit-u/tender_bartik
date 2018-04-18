<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TaskController extends Controller

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
        if(request()->user()->can('view',Task::class)){
            return Task::all();
        }
        return response()->json(['message' => 'Not authorized.'],403);
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
        if(request()->user()->can('create',Task::class)) {
            // validate
            // read more on validation at http://laravel.com/docs/validation
            $rules = array(
                'name' => 'required',
                'status' => 'nullable',
                'assignee' => 'required',
                'assigner' => 'required',
                'description' => 'nullable',
            );
            $validator = Validator::make($request->all(), $rules);

            // process the login
            if ($validator->fails()) {
                return response()->json(['message' =>'form not valid','error'=>$validator->errors()]);
            }
//            return $request->user()->id;
//            return $request->user()->id;
            $isSub = false;
            $subs = $request->user()->subordinates()->get();
            foreach ($subs as &$sub){
                if ($sub->id == $request->input('assignee')){
                    $isSub = true;
                    break;
                }
            }
            $isSup = false;
            $sups = User::find($request->input('assignee'))->supervisors()->get();
//            return $sups;
            foreach ($sups as &$sup){
                if ($sup->id == $request->input('assigner')){
                    $isSup = true;
                    break;
                }
            }
//            return ($isSup) ? 'true' : 'false';
            if (!$isSub || !$isSup){
                return response()->json(['message' =>'not sub of sup']);
            }
//            return $subs;
            // store
            $task = new Task;
            $task->name = $request->input('name');
            $task->status = 'created';
            $task->description = $request->input('description');
            $task->assignee = $request->input('assignee');
            $task->assigner = $request->input('assigner');
            $task->save();

//            return redirect()->route('login');
            return response()->json(['message' => 'Successfully created Task!']);
        }
        return response()->json(['error' => 'Not authorized.'],403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        return Task::findOrFail($id);;
        $task = Task::findOrFail($id);
        if(request()->user()->can('show',$task)){
            return $task;
        }
        return response()->json(['message' => 'Not authorized.'],403);
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
     * @param  \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        return $request;
        if(request()->user()->can('create',Task::class)) {
            // validate
            // read more on validation at http://laravel.com/docs/validation
            $rules = array(
                'name' => 'required',
                'status' => 'nullable',
                'assignee' => 'nullable',
                'assigner' => 'required',
                'description' => 'nullable',
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
            $task = Task::find($id);
            $task->name = $request->input('name');
            $task->status = 'created';
            $task->description = $request->input('description');
            $task->assignee = $request->input('assignee');
            $task->assigner = $request->input('assigner');
            $task->save();

            // redirect
//            $request->session()->flash('message', 'Successfully created nerd!');
//            return redirect()->route('login');
            return response()->json(['message' => 'Successfully update Task!']);

        }
        return response()->json(['message' => 'Not authorized.'],403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete
        $task = Task::find($id);
        $task->delete();

        // redirect

        return response()->json(['message' => 'Successfully created Task!']);
    }
}
