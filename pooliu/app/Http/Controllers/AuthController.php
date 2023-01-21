<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Events\Registered;
use App\Mail\VerificationCode;
use App\Models\User;
use Auth;

use DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\ForgotMail;

class AuthController extends Controller
{

    public function VerificationEmail($email, $code){

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


    public function Register(Request $request){
        $this->validate($request, [
            'LIU_ID' => 'required|numeric',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'is_LIU' => 'required|boolean'
        ]);

        try{           

            $liu_id = $request->LIU_ID;
            $password = bcrypt($request->password); 
            $is_liu = $request->is_LIU;
            $email = $liu_id.'@students.'.(($is_liu) ? 'liu' : 'biu').'.edu.lb';
            $code = random_int(1000, 9999);
            Cache::put('verification_code', $code, now()->addMinutes(180));

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


    public function Verify(Request $request){

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
                //$token = auth()->$user->createToken('AuthToken')->accessToken;

                if($entered_code == $user->verification_num){

                    $user->verification_status = 1; 
                    $user->save();

                    Auth::logout();
            
                    return response([
                        'message' => "Registration Successfull",
                        'token' => $token,
                        'user' => $user,
                        'check' => Auth::check()
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


    public function Login(Request $request){
        try{
            $data = [
                'LIU_ID' => $request->LIU_ID,
                'password' => $request->password,
                'verification_status' => 1
            ];

            if (auth()->attempt($data)) {
                $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
                $user = Auth::user();

                Auth::login($user);

                return response([
                    'message' => "Successfully Login",
                    'token' => $token,
                    'user' => $user,
                    'check' => Auth::check()
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

    
    public function Logout(Request $request){
        try{
            Auth::logout();

            //Auth::user()->token()->revoke();
                
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


    public function ForgotPassword(Request $request){
    	$liu_id = $request->LIU_ID;
        $is_liu = $request->is_LIU;
        $email = $liu_id.'@students.'.(($is_liu) ? 'liu' : 'biu').'.edu.lb';

    	if (User::where('email',$email)->doesntExist()) {
    		return response([
    			'message' => 'Invalid LIU ID or wrong email type'
    		],401);
    	}

    	// generate Randome Token 
    	$token = rand(10,100000);

    	try{
    		DB::table('password_resets')->insert([
    			'email' => $email,
    			'token' => $token
    		]);

    		// Mail Send to User 
    		Mail::to($email)->send(new ForgotMail($token));

    		return response([
    			'message' => 'Reset Password Mail send on your email'
    		],200);

    	}catch(Exception $exception){
    		return response([
    			'message' => $exception->getMessage()
    		],400);
    	}
    }


    public function ResetPassword(Request $request){

        if($request->password === $request->password_confirmation){
            $email = $request->email;
            $token = $request->token;
            $password = Hash::make($request->password);

            $emailcheck = DB::table('password_resets')->where('email',$email)->first();
            $pincheck = DB::table('password_resets')->where('token',$token)->first();

            if (!$emailcheck) {
                return response([
                    'message' => "Email Not Found"
                ],401);    	 	 
            }
            if (!$pincheck) {
                return response([
                    'message' => "Pin Code Invalid"
                ],401);    	 	 
            }

            DB::table('users')->where('email',$email)->update(['password' => $password]);
            DB::table('password_resets')->where('email',$email)->delete();

            return response([
                'message' => 'Password Change Successfully'
            ],200);
        }
        return response([
            'message' => "Passwords don't match"
        ],401); 

    }


    public function Edit(Request $request){

        $old_id = $request->old_id;
        $new_id = $request->new_id;

        DB::table('users')->where('LIU_ID',$old_id)->update(['LIU_ID' => $new_id]);

        return response([
            'message' => 'ID Changed Successfully'
        ],200);

    }

}
