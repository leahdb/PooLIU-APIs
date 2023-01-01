<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;

use Illuminate\Auth\Events\Registered;
use App\Mail\VerificationCode;
use App\Models\User;
use Auth;

class AuthController extends Controller
{

    public function verificationEmail($email, $code){

        $data = [
            'subject'=>'Your PooLIU account verification code',
            'body'=>'Your verification code is: ' . $code
        ];
        try{
            Mail::to($email)->send(new VerificationCode($data));
            return response([
                'message' => "Great check your mail box"
            ], 200);
        }catch(Exception $th){
            return response([
                'message' => "sorry something went wrong"
            ], 400);
        }
    }


    public function register(Request $request){
        $this->validate($request, [
            'LIU_ID' => 'required|numeric',
            'password' => 'required|min:6',
            'is_LIU' => 'required|boolean'
        ]);

        try{           

            $liu_id = $request->LIU_ID;
            $password = bcrypt($request->password); 
            $is_liu = $request->is_LIU;
            $email = $liu_id.'@students.'.(($is_liu) ? 'liu' : 'biu').'.edu.lb';
            $code = random_int(1000, 9999);

            $user = User::create([
                'LIU_ID' => $liu_id,
                'password' => $password,
                'is_LIU' => $is_liu,
                'email' => $email,
                'verification_num' => $code
            ]);
        
            $token = $user->createToken('LaravelAuthApp')->accessToken;
            
            $this->verificationEmail($email, $code);
            

        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }


    public function verify(Request $request){

        try{
            $entered_digits = [$request->digit1, $request->digit2, $request->digit3, $request->digit4];
            $entered_code = implode('', $entered_digits);

            $data = [
                'verification_num' => $entered_code,
                'password' => $request->password
            ];

            if (Auth::attempt($data)) {
                $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
                $user = Auth::user();

                if($entered_code == $user->verification_num){

                    $user->verification_status = 1; 
                    $user->save();

                    Auth::logout();
            
                    return response([
                        'message' => "Registration Successfull",
                        'token' => $token,
                        'user' => $user
                    ],200);

                }
                return response([
                    'message' => "invalid code",
                    'sent code' => $entered_code,
                    'entered code' => $user->verification_num,
                ],400);

            }

        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }


    public function login(Request $request){
        try{
            $data = [
                'LIU_ID' => $request->LIU_ID,
                'password' => $request->password,
                'verification_status' => 1
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
    
    
    public function logout(Request $request){
        try{
            Auth::logout();
                
            redirect('/');

            return response([
                'message' => "Successfully Logout",
            ],200); // States Code

        }catch(Exception $exception){
            return response([
    			'message' => $exception->getMessage()
    		],400);
        }
        
    }
}
