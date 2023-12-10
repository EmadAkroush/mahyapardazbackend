<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
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
        $validator = Validator::make($request->all() , [
            'name' => 'required',
            'password' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages() , 422);
        }

        $user = User::where('name' , $request->name)->first();

        if(!$user){
            return response()->json('user not found' , 401);
        }

        if($request->password != $user->password){
            return response()->json('password is incorrect' , 401);
        }

        $token = $user->createToken('myApp')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ] , 200);

    }

    public function me()
    {
        if (auth()->check()) return $this->respondWithToken();

        return response()->json([], 401);
    }

    public function logout()
    {
        $user = auth()->user();
        auth()->logout();

        return response()->mahyaJson(['message' => 'خارج شدید .'], Response::HTTP_OK);
    }

    public function refresh()
    {
        return $this->respondWithToken();
    }

    protected function respondWithToken($message = null)
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'access_token' => $user->access_token,
            'token_type' => 'bearer',
            'message' => $message,
            'is_success' => true,
        ]);
    }
}
