<?php

namespace App\Http\Controllers\Api;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Notifications\RegisterNotify;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public $successStatus = 200;
/*
    public function signup(Request $request)
    {
        // validate inputs
    
        // store new user & send verify email notification to user
        $user = User::create([
            
            'name' => $request->name,
            'email'  => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        $user->notify(new RegisterNotify());

    
        $user->sendEmailVerificationNotification();
    

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user'=> $user, 'access_token'=> $accessToken]);
        // assign access token to newly registered user
        
        // return access token & user data
        
    }
     
*/

/*

public function signup(Request $request)
{
     $validatedData = $request->validate([
         'name'=>'required|max:55',
         'email'=>'email|required|unique:users',
         'password'=>'required|confirmed'
     ]);

     $validatedData['password'] = bcrypt($request->password);

     $user = User::create($validatedData);

     $user->notify(new RegisterNotify());

     $user->sendEmailVerificationNotification();

     $accessToken = $user->createToken('authToken')->accessToken;

     return response(['user'=> $user, 'access_token'=> $accessToken]);
    
}
*/


public function signup (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);
    if ($validator->fails())
    {
        return response(['error'=>$validator->errors()->all()], 422);
    }
    $request['password']=Hash::make($request['password']);
    $request['remember_token'] = Str::random(10);
    $user = User::create($request->toArray());
    $user->notify(new RegisterNotify());

    $user->sendEmailVerificationNotification();

    $accessToken = $user->createToken('authToken')->accessToken;

    return response(['user'=> $user, 'access_token'=> $accessToken]);
}

public function login(){ 
    if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
       $user = Auth::user(); 
       $accessToken = auth()->user()->createToken('authToken')->accessToken;
       return response(['user' => auth()->user(), 'access_token' => $accessToken]);
     } else{ 
       return response()->json(['error'=>'mdp ou email incorrect'], 401); 
       } 
    }

}

   


