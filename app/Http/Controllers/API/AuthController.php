<?php
namespace App\Http\Controllers\API;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function signup(Request $request)
    {
        $validateuser=Validator::make(
            $request->all(),[
                'name'=>'required',
                'email'=>'required|email|unique:users,email',
                'password'=>'required'
            ]
            );
           
            if($validateuser->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'validation error',
                    'error'=>$validateuser->errors()->all()

                ],401);
            }
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>$request->password
            ]);
            return response()->json([
                'status'=>true,
                'message'=>'user created successfully',
                'user'=>$user

            ],200);

    }
    public function login(Request $request)
    {
        $validateuser=Validator::make(
            $request->all(),[
                'email'=>'required|email',
                'password'=>'required'
            ]
            );
            if($validateuser->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'Authatication fails',
                    'error'=>$validateuser->errors()->all()
                ],404);
            }
            if(Auth::attempt(['email'=>$request->email,'password'=>$request->password]))
            {
                $authUser=Auth::user();
                return response()->json([
                    'status'=>true,
                    'message'=>'user logged in successfully',
                    'token'=>$authUser->createToken('App Token')->plainTextToken,
                    'token_type'=>'bearer'
    
                ],200);

            }
            else{
                return response()->json([
                    'status'=>fail,
                    'message'=>'Username and Password not match',
                ],401);
            }

    }
    public function logout(Request $request){
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'status'=>true,
            'user'=>$user,
            'message'=>'You logged out Successfully',
        ],401);


    }
}
