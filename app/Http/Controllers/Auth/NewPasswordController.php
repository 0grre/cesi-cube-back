<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class NewPasswordController extends Controller
{
    /**
     * @param $token
     * @return JsonResponse
     */
    public function create($token): JsonResponse
    {
        return $this->sendResponse((['token' => __($token)]), 'Password reset token sent successfully');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:4',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $email_password_status = Password::reset($request->all(), function($user, $password){
            $user->password = Hash::make($password);
            $user->save();
        });

        if($email_password_status == Password::INVALID_TOKEN){
            $this->sendError('Invalid token provided');
        }

        return $this->sendResponse(UserResource::make(User::where('email', $request->email)->first()),
            'User password updated successfully.');
    }
}
