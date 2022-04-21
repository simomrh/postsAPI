<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    public function sendResponse($result , $message){
       $response = [
           'success'=> true,
           'data'=> $result,
           'message'=> $message
       ];
       return response()->json($response , 200);
    }


    public function sendError($errors , $errorMessage){
        $response = [
            'success'=> false,
            'message'=> $errors
        ];
        if(!empty($errorMessage)){
            $response['data'] = $errorMessage;
        }
        return response()->json($response , 400);
     }
}
