<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function default(Request $request)
    {
        return response()->json([
            'message' => 'Bad credentials.'
        ], 401);
    }

    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
        	return response()->json([
			    'errors' => $validator->errors()->first()
			], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user && Hash::check($user->password, $request->password)) {
        	return response()->json([
			    'errors' => 'Bad credentials.'
			], 409);
        }

    	if ($user->hasToken()) {
            $user->tokens()->delete();	
    	}

		return response()->json([
			'token' => $user->createToken('API Access')->plainTextToken
		], 200);
    }

    public function logout(Request $request)
    {
    	$request->user()->currentAccessToken()->delete();

    	return response()->json(null, 204);
    }

    public function register(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
        	return response()->json([
			    'errors' => $validator->errors()->first()
			], 422);
        }

        if (User::where('email', $request->email)->exists()) {
        	return response()->json([
			    'errors' => 'This email is already linked to an account.'
			], 409);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name
        ]);

        return response()->json([
			'token' => $user->createToken('API Access')->plainTextToken
		], 201);
    }

    public function getUserData(Request $request)
    {
    	return response()->json([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'created_at' => $request->user()->created_at,
            'updated_at' => $request->user()->updated_at
        ], 200);
    }
}
