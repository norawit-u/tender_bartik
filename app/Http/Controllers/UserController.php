<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Laravel\Passport\Bridge\Client;

class UserController extends Controller
{
    /**
     * The user repository instance.
     */
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @param  UserRepository  $users
     * @return void
     */
//    public function __construct(User $users)
//
//        $this->users = $users;
//    }
    /**
     * Display a listing of the resource.
     *
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    function create(Request $request)
    {
        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array  $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        $valid = validator($request->only('email', 'name', 'password','mobile'), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'telno' => 'required',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'fb' => 'nullable|string|max:255',
            'ig' => 'nullable|string|max:255',
            'line' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $data = request()->only('email','name','password','mobile');

        $user = User::create([
            'name' => $data['fname'] .  $data['lname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'mobile' => $data['mobile']
        ]);

//        // And created user until here.
//
//        $client = Client::where('password_client', 1)->first();
//
//        // Is this $request the same request? I mean Request $request? Then wouldn't it mess the other $request stuff? Also how did you pass it on the $request in $proxy? Wouldn't Request::create() just create a new thing?
//
//        $request->request->add([
//            'grant_type'    => 'password',
//            'client_id'     => $client->id,
//            'client_secret' => $client->secret,
//            'username'      => $data['email'],
//            'password'      => $data['password'],
//            'scope'         => null,
//        ]);
//
//        // Fire off the internal request.
//        $token = Request::create(
//            'oauth/token',
//            'POST'
//        );
        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json(request()->user());
//        return User::findOrFail($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }



}
