<?php

namespace App\Http\Controllers;
use App\Models\User;
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
    public function register(Request $request)
{
    $validate = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8'
    ]);

    if ($validate->fails()) {
        return response()->json([
            'data' => $validate->errors(),
            'title' => 'Error de validación'
        ], 422);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
    ]);


    Auth::login($user);

    return response()->json([
        'token' => $user->createToken('auth_token')->plainTextToken,
        'token_type' => 'Bearer',
        'title' => 'Registro exitoso',
        'msg' => 'Usuario registrado correctamente'
    ], 201);
}
}
