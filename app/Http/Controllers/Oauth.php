<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Oauth extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function login(Request $request){
        $url = 'limitless-falls-39048.herokuapp.com/oauth/token';
//        $data = array(
//            'client_secret' => 'utjoZvK6I9lCADRw8XpWgsbgVyeoKy1Yt2uYcqVl',
//            'grant_type' => 'password',
//            'client_id' => '2',
//            'username' => $request->input('username'),
//            'password' => $request->input('password')
//        );
//
//        // use key 'http' even if you send the request to https://...
//        $options = array(
//            'http' => array(
////                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
//                'method'  => 'POST',
//                'content' => http_build_query($data)
//            )
//        );
////        return $options;
//        $context  = stream_context_create($options);
////        $result = http_post_data($url, false, $context);

        $client = DB::table('oauth_clients')->where('id', 1)->first();
        $http = new GuzzleHttp\Client;
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '1',
                'client_secret' =>  $client->secret,
                'username' => $request->input('username'),
                'password' => $request->input('password')
            ],
            'http_errors' => false
        ]);
        return json_decode((string) $response->getBody(), true);
    }
    /**
     * Register api
     *
     * @return \Psr\Http\Message\ResponseInterface
*/
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'telno' => 'required|string|max:255',
            'fb' => 'required|string|max:255',
            'ig' => 'required|string|max:255',
            'line' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'supervisor_id' => 'nullable',
            'password' => 'required|string|min:6',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create([
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
            'supervisor_id' => $request->input('supervisor_id'),
            'password' => bcrypt($request->input('password')),
            'image_path' => ''
        ]);
        if($request->input('role') == 'Subordinate'){
            $user->depatemanet = User::find($request->input('supervisor_id'))->depatemanet;
        }
        $http = new GuzzleHttp\Client;
        $client = DB::table('oauth_clients')->where('id', 1)->first();
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '1',
                'client_secret' =>  $client->secret,
                'username' => $request->input('email'),
                'password' => $request->input('password')
            ],
            'http_errors' => false
        ]);
        return json_decode((string) $response->getBody(), true);
    }

//    public function changePassword(Request $request) {
//        $data = $request->all();
//        $user = $request->user();
//        return 'aa';
//        //Changing the password only if is different of null
//        if( isset($data['oldPassword']) && !empty($data['oldPassword']) && $data['oldPassword'] !== "" && $data['oldPassword'] !=='undefined') {
//            //checking the old password first
//            $check  = Auth::guard('web')->attempt([
//                'username' => $user->username,
//                'password' => $data['oldPassword']
//            ]);
//            if($check && isset($data['newPassword']) && !empty($data['newPassword']) && $data['newPassword'] !== "" && $data['newPassword'] !=='undefined') {
//                $user->password = bcrypt($data['newPassword']);
//                $user->isFirstTime = false; //variable created by me to know if is the dummy password or generated by user.
//                $user->token()->revoke();
//                $token = $user->createToken('newToken')->accessToken;
//
//                //Changing the type
//                $user->save();
//
//                return json_encode(array('token' => $token)); //sending the new token
//            }
//            else {
//                return "Wrong password information";
//            }
//        }
//        return "Wrong password information";
//    }

    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6',
            'new_c_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $data = $request->all();
        $user = $request->user();

        //Changing the password only if is different of null
        if( isset($data['old_password']) && !empty($data['old_password']) && $data['old_password'] !== "" && $data['old_password'] !=='undefined') {
            //checking the old password first
            $http = new GuzzleHttp\Client;
            $client = DB::table('oauth_clients')->where('id', 1)->first();
            $check = $http->post(url('oauth/token'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => '1',
                    'client_secret' =>  $client->secret,
                    'username' => $request->user()->email,
                    'password' => $request->input('old_password')
                ],
                'http_errors' => false
            ]);
            return $check->getBody();
            if($check->getBody()->{'error'} != ''){
                return "aa";
            }
            return $check;
            if($check && isset($data['new_password']) && !empty($data['new_password']) && $data['new_password'] !== "" && $data['new_password'] !=='undefined') {
                $user->password = bcrypt($data['new_password']);
                $user->isFirstTime = false; //variable created by me to know if is the dummy password or generated by user.
                $user->token()->revoke();
                $token = $user->createToken('newToken')->accessToken;

                //Changing the type
                $user->save();

                return json_encode(array('token' => $token)); //sending the new token
            }
            else {
                return "Wrong password information";
            }
        }
        return "Wrong password information";
    }
}
