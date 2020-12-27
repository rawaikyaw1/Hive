<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;

class LoginController extends Controller
{
    

    public function userLogin(Request $request)
    {
        $validator = Validator::make(request()->all(), 
        array(
            'email'  =>      'required|email',
            'password' =>    'required|min:6'
        ));

        if($validator->fails()){
            
            return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()->toArray()
             ]);

        }
        else{
            
            $user = User::where('email', $request->email )->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                
             return response()->json([
                 'success' => false,
                 'message' => 'Invalid Credential'
             ]);
            
            }

            $is_admin = false;
            if ($user->is_admin) {
                $is_admin = true;
            }


            return response()->json([
                 'success' => true,
                 'token' => $user->createToken('Auth Token')->accessToken,
                 'is_admin' => $is_admin
             ]);
            
            

        }
           
        

    }

    public function logout() {
        
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });

        return response()->json([
             'success' => true,
             'message' => 'Successfully logged out!'
         ]);
    }

    // public function checkSession(Request $request)
    // {
    //     if(session('is_admin'))
    //         return true;
    //     else
    //         return false;
    // }
}
