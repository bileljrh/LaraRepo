<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;




use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;




class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */


    use ResetsPasswords;

    public function reset(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255' ],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);


        if ($validator->fails()) {
            //return response()->json(['error' => $validator->errors()], 422);

            return response(['error'=>'incorrect inputs, please check it.'],422);

        }
        
        //$request->validate($this->rules(), $this->validationErrorMessages());

      
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }
  
    protected function resetPassword($user, $password) 
    { 
        $this->setUserPassword($user, $password); 
        

        $user->save(); 
        event(new PasswordReset($user)); 
        
    }

    
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['success' => ["message" => trans($response)] ], 200);                          
    }

   
    protected function sendResetFailedResponse(Request $request, $response)
    {

        return response(['error'=> trans($response)], 422);            
    }

}
