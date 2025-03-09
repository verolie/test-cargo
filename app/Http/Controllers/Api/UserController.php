<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponseTrait;
use App\Models\User;

class UserController extends Controller
{
    use ApiResponseTrait;

    // Endpoint update: PUT /api/user
    public function update(Request $request)
    {
        try {
            $id = $request->query('id');

            if (!$id) {
                return $this->errorResponse('User ID is required', 400);
            }
            $user = User::find($id);

            $validator = Validator::make($request->all(), [
                'name'     => 'sometimes|required|string|max:255',
                'email'     => 'sometimes|required|string|max:255',
                'password' => 'sometimes|required|string|min:6|confirmed',
                'roleId'   => 'sometimes|nullable|exists:roles,id',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            if ($request->has('name')) {
                $user->name = $request->name;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($request->has('roleId')) {
                $user->roleId = $request->roleId;
            }

            $user->save();

            return $this->successResponse(
                'User updated successfully',
                200,
                new UserResource($user)
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // Endpoint delete: DELETE /api/user
    public function destroy(Request $request)
    {
        try {
            $id = $request->query('id');

            if (!$id) {
                return $this->errorResponse('User ID is required', 400);
            }

            $user = User::find($id);

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            $user->delete();

            return $this->successResponse(
                'User deleted successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
