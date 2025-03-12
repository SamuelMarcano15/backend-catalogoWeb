<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email'           => 'required',
            'password'        => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'data'=>$validate->errors(),
                'title'=>'Por favor verifique los datos solicitados'
            ],400);
        }
        if (Auth::attempt($request->only('email','password'))){
            return response()->json([
                'token'=>$request->user()->createToken(Hash::make('token'),['server:update'])->plainTextToken,
                'token_type'=>'Bearer',
                'title' => 'Inicio de sesión exitoso',
                'msg'=>''
            ],200);
        }
        return response()->json([
            'title'=>'Datos incorrectos',
            'msg'=>'Verifique los datos ingresados'
        ],400);
    }
    // Ya con eso replicas para lo demás como? Si, solo sería tipo esto
    public function register (Request $r) {
        //$r->all()['nombrekey']
        $user = User::create([
            'parametroDeBaseDeDatos'=>'valor'
        ]); //colocas a penas abras una terminal lo del alias sail='' para que reconozca el comando, vaya con su bdd
    }
}
