<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        return response()->json(User::pluck('username'), 200);
    }
    public function userTask()
    {
        // $user = User::all();

        $users = User::has('tasks')->with('tasks')->get();


        return response()->json($users, 200);
    }
}
