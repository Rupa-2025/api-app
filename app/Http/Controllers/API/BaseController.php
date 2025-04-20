<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    //
    public function sendResponse($data,$message){
        $response=[
            'status'=>true,
            'data'=>$data,
            'message'=>$message,
        ];
        return response()->json($response,200);
    }
    public function sendError($error,$errorMessage=[],$code=404){
        $response=[
            'status'=>false,
            'message'=>$error,
        ];
        if(!empty($errorMessage)){
            $response['data']=$errorMessage;
        }
        return response()->json($response,$code);
    }
}
