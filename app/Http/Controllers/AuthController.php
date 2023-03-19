<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AuthController extends Controller
{   
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|string',
            'surname'=>'required|string',
            'patronymic'=>'required|string',
            'login'=>'required|string',
            'password'=>'required|string',
            'photo_file'=>'required|string',
            'role_id'=>'required|string',
        ]);
        if ($validator->fails()){
            return response()->json([
                'error'=>(object)[
                    'code'=>422,
                    'message'=>'Validation error',
                    'errors'=>'$validator->eroros()'
                ]
            ],422);
        }
        $user = new Users();
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->patronymic = $request->input('patronymic');
        $user->login = $request->input('login');
        $user->password = $request->input('password');
        $user->photo_file = $request->input('photo_file');
        $user->role_id = $request->input('role_id');
        $user->save();
        
        return response()->json([
            'data'=>(object)[
                'id'=>$user->id,
                'status' => 'created',
            ]
        ]);
        
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'login' => 'required|string',
            'password'=>'required|string',
        ]);
        if ($validator->fails()){
            return response()->json([
                'error'=>(object)[
                    'code'=>422,
                    'message'=>'Validation error login',
                    'errors'=>'$validator->eroros()'
                ]
            ],422);
        }
        $login = $request->input('login');
        $password = $request->input('password');
        if ($user = Users::where('login', $login)->first()){
            if ($password === $user->password){
                $token = Str::random(50);
                $user->api_token = $token;
                $user->save();
                return response()->json([
                    'date'=>(object)[
                        'token'=>$token
                    ]
                    ],200);
            }
        }
        return respons()->json([
            'error'=>(object)[
                'code'=>401,
                'message'=>'Unauthorized',
                'errors'=>(object)[
                    'login'=>'login or password incorrect' 
                ]
            ]
                ],401);

    }

    public function logout(Request $request){
        dd("111111");
    }
}