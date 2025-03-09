<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponseTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    use ApiResponseTrait;

    // Endpoint registrasi: POST /api/register
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'roleId'   => 'nullable|exists:roles,id'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'roleId'   => $request->roleId
            ]);

            return $this->successResponse(
                'User registered successfully',
                201,
                new UserResource($user)
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // Endpoint login: POST /api/login
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {

                return $this->errorResponse('Invalid credentials', 401);
            }

            return $this->successResponse(
                'Login successful',
                200,
                [
                    'access_token' => $token,
                    'token_type'   => 'bearer',
                    'user'         => new UserResource(Auth::user())
                ]
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // Endpoint check email: GET /api/check-email?email=example@example.com
    public function checkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $exists = User::where('email', $request->email)->exists();

            return $this->successResponse(
                'Email check successful',
                200,
                ['email_exists' => $exists]
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // Endpoint untuk mengambil detail user (token harus disertakan)
    public function user(Request $request)
    {
        try {
            return $this->successResponse(
                'User details retrieved',
                200,
                new UserResource($request->user())
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
