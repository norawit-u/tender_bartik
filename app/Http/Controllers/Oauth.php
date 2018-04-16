<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use GuzzleHttp;
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
            'line' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
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
            'password' => bcrypt($request->input('password')),

        ]);
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

}
