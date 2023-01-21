<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;


class ProfileController extends AuthController
{
    public function Setup(Request $request){
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_num' => 'required|numeric',
            'gender' => 'required|boolean'
        ]);

        try{      

            $first_name = $request->first_name;
            $last_name = $request->last_name;
            $phone_num = $request->phone_num;
            $profile_pic = $request->file('profile_pic');
            //$profile_pic = $request->profile_pic;
            $gender = $request->gender;

            DB::table('users')->where('LIU_ID',$request->LIU_ID)->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone_num' => $phone_num,
                'profile_pic' => $profile_pic,
                'gender' => $gender,
            ]);

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
            'phone_num' => 'required|numeric'
        ]);

        try{      
            $first_name = $request->first_name;
            $last_name = $request->last_name;
            $phone_num = $request->phone_num;
            $profile_pic = $request->file('profile_pic');
            //$profile_pic = $request->profile_pic;
            $gender = $request->gender;

            DB::table('users')->where('LIU_ID',$request->LIU_ID)->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone_num' => $phone_num,
                'profile_pic' => $profile_pic,
            ]);

        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }

    }
    
    public function show(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'liu_id' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->all(), 400);
            }

            $query = User::query()->get();

            $query = $query->where('LIU_ID', $request->liu_id);

            $user = (new UserCollection($query))->response()->getData();

            return response([
                'message' => "User retrieved successfully",
                'user' => $user
            ],200);


        }catch(Exception $ex){

        }
        return new UserResource($user);
    }
}
