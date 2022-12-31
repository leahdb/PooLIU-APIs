<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'LIU_ID' => 'required|numeric',
            'password' => 'required|min:6',
            'is_LIU' => 'required|boolean'
        ]);

        try{

            $user = User::create([
                'LIU_ID' => $request->LIU_ID,
                'password' => bcrypt($request->password),
                'is_LIU' => $request->is_LIU
            ]);
        
            $token = $user->createToken('LaravelAuthApp')->accessToken;
    
            return response([
                'message' => "Registration Successfull",
                'token' => $token,
                'user' => $user
            ],200);

        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function Loginm(Request $request){

    	try{

    		if (Auth::attempt($request->only('email','password'))) {
    			$user = Auth::user();
    			$token = $user->createToken('app')->accessToken;

    			return response([
    				'message' => "Successfully Login",
    				'token' => $token,
    				'user' => $user
    			],200); // States Code
    		}

    	}catch(Exception $exception){
    		return response([
    			'message' => $exception->getMessage()
    		],400);
    	}
    	return response([
    		'message' => 'Invalid Email Or Password' 
    	],401);

    }

    public function login(Request $request)
    {
        try{
            $data = [
                'LIU_ID' => $request->LIU_ID,
                'password' => $request->password
            ];

            if (auth()->attempt($data)) {
                $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
                $user = Auth::user();

                return response([
                    'message' => "Successfully Login",
                    'token' => $token,
                    'user' => $user
                ],200); // States Code
            }


    	}catch(Exception $exception){
    		return response([
    			'message' => $exception->getMessage()
    		],400);
    	}
    	return response([
    		'message' => 'Invalid Email Or Password' 
    	],401);
    }   
}
