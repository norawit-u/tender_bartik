<?php

namespace App\Http\Controllers;

use App\User;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
//        return response()->json(request()->user());
        return User::findOrFail($user);
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
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'telno' => 'required|string|max:255',
            'fb' => 'required|string|max:255',
            'ig' => 'required|string|max:255',
            'line' => 'nullable|string|max:255',
            'department' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = $request->user()
//            'name' => ($request->input('fname') . ' ' . $request->input('lname')),
            'name' => 'aaa',
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'address' => $request->input('address'),
            'telno' => $request->input('telno'),
            'fb' => $request->input('fb'),
            'ig' => $request->input('ig'),
            'line' => $request->input('line'),
            'department' => $request->input('department'),
            'role' => $request->input('role'),
            'email' => $request->input('email'),
        ]);
        $user->save();
        return json_decode((string) $user->getBody(), true);
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

    public function tasks(){
        return response()->json(request()->user()->tasks());
    }
    public function leaves(){
        return response()->json(request()->user()->leaves());
    }
    public function current(){
        return response()->json(request()->user());
    }

    public function uploadImage(Request $request){
        $rules = array(
            'images' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return response()->json(['message' => 'form not valid', 'error' => $validator->errors()]);
        }

        $url = 'storage/' . (string)$request->images->store('images','public');
        $request->user()->image_path = $url;
        $request->user()->save();
        return $url;
//        return storage_path($request->images->store('images','public'));

//
//        $image      = $request->file('images');
//        $fileName   = time() . '.' . $image->getClientOriginalExtension();
//
//        $img = Image::make($image->getRealPath());
////        $img->resize(120, 120, function ($constraint) {
////            $constraint->aspectRatio();
////        });
//
//        $img->stream(); // <-- Key point
//
//        //dd();
//        Storage::disk('local')->put('images/'.$fileName, $img, 'public');
//        return 'images/'.$fileName;
    }

    public function me(Request $request){
        return $request->user();
    }

}
