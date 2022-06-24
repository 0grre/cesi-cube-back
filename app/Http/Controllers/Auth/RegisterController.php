<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Register api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4',
            'firstname' => 'string|min:2|max:55',
            'lastname' => 'string|min:2|max:55',
            'address1' => 'string|min:2|max:255',
            'address2' => 'string|min:2|max:255|nullable',
            'zipCode' => 'regex:/\w{20}/',
            'city' => 'string|min:2|max:55',
            'primaryPhone' => 'string',
            'secondaryPhone' => 'string|nullable',
            'birthDate' => 'date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('CesiCube')->plainTextToken;
        $success['user'] = $user;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('CesiCube')->plainTextToken;
            $success['user'] = $user;

            return $this->sendResponse($success, 'User login successfully.');
        }

        return $this->sendError('login failed', ['error' => 'Unauthorized']);
    }

    /**
     * Login failed
     *
     * @return JsonResponse
     */
    public function login_failed(): JsonResponse
    {
        return $this->sendError('Access forbidden', ['error' => 'Unauthorized']);
    }
}
