<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'login',
                'register'
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        $token = Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'user registered succesfully',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        return response()->json([
            'status' => 'success',
            'message' => 'user logged in succesfully',
            'token' => $token,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();

        return response()->json([
            'status' => 'success',
            'message' => 'user logged out succesfully',
        ]);
    }
}
