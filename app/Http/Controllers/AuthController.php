<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\User as Authenticatable;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register' , 'test']]);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required|string',
            'family' => 'required',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        // |email|unique:users,email
        if($validator->fails()){
            return response()->json($validator->messages() , 422);
        }

        $user = User::create([
            'name' => $request->name,
            'family' => $request->family,
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('myApp')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ] , 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|string'
        ], $request->all());


        $credentials = $request->only(['username', 'password']);
         
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'نام کاربری یا رمز عبور اشتباه است .'], 401);
        }

        return $this->respondWithToken($token);

    }

    public function test(Request $request)
    {
       return User::all();
    }

    public function me()
    {
        if (auth()->check()) return $this->respondWithToken(auth()->refresh());

        return response()->json([], 401);
    }

    public function logout()
    {
        auth()->logout();

        return response()->mahyaJson(['message' => 'خارج شدید .'], Response::HTTP_OK);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token, $message = null)
    {
        return response()->json([
            'user' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'message' => $message,
            'is_success' => true,
        ]);
    }
}
