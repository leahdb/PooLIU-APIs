<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Http\Resources\UserResource;
use Auth;
use DB;


class ProfileController extends AuthController
{
    public function Setup(Request $request){
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_num' => 'required|numeric',
            'profile_pic' => 'image',
            'gender' => 'required|boolean'
        ]);

        try{           
            $data = [
                'id' => $request->LIU_ID,
                'password' => $request->password
            ];

            if (Auth::attempt($data)) {

                $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
                $user = Auth::user();

                $first_name = $request->first_name;
                $last_name = $request->last_name;
                $phone_num = $request->phone_num;
                $profile_pic = $request->profile_pic;
                $gender = $request->gender;

                $user->update([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone_num' => $phone_num,
                    'profile_pic' => $profile_pic,
                    'gender' => $gender,
                ]);
            
                return response([
                    'message' => 'profile set successfully',
                    'user' => $user
                ], 200);
            }

        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }

    }

    public function Edit(Request $request){
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_num' => 'required|numeric',
            'profile_pic' => 'image',
            'gender' => 'required|boolean'
        ]);

        try{           
            $data = [
                'id' => $request->LIU_ID,
                'password' => $request->password
            ];

            if (Auth::attempt($data)) {

                $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
                $user = Auth::user();

                $first_name = $request->first_name;
                $last_name = $request->last_name;
                $phone_num = $request->phone_num;
                $profile_pic = $request->profile_pic;
                $gender = $request->gender;

                $user->update([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone_num' => $phone_num,
                    'profile_pic' => $profile_pic,
                    'gender' => $gender,
                ]);
            
                return response([
                    'message' => 'profile set successfully',
                    'user' => $user
                ], 200);
            }

        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }

    }
    
    public function show(User $user)
    {
        return new UserResource($user);
    }
}
