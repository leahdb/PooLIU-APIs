<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

use App\Mail\VerificationCode;

class MailController extends Controller
{
    public function verification(){

        $code = [];
        for($i=0; $i<4; $i++){
            $code[$i] = random_int(0, 9);
        }

        $data = [
            'subject'=>'Your PooLIU account verification code',
            'body'=>'Your verification code is: ' . $code[0] .$code[1] . $code[2] . $code[3]
        ];
        try{
            Mail::to('12133363@students.liu.edu.lb')->send(new VerificationCode($data));
            return response()->json(['Great check your mail box']);
        }catch(Exception $th){
            return response()->json(['sorry something went wrong']);
        }
    }
}
