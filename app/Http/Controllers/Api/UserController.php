<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->sendResponse(New Paginator(UserResource::collection(User::all()), 10), 'Users found successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->UserValidator($request);
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users|required',
            'password' => 'string|min:4|required',
            'role' => 'string|required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }
        $user = new User();

        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->firstname = $request->firstname ?? '';
        $user->lastname = $request->lastname ?? '';
        $user->address1 = $request->address1 ?? '';
        $user->address2 = $request->address2 ?? '';
        $user->zipCode = $request->zipCode ?? '';
        $user->city = $request->city ?? '';
        $user->birthDate = $request->birthDate ?? '';
        $user->primaryPhone = $request->primaryPhone ?? '';
        $user->secondaryPhone = $request->secondaryPhone ?? '';
        $user->save();

        $user->assignRole($request->role);

        return $this->sendResponse($this->UserValidator($request), 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $user = User::find($id);

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse(UserResource::make($user), 'User found successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $this->UserValidator($request);
        $user = User::find($id);

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        if (!self::check_owner($user)) {
            return $this->sendError('Validation Error.', (array)'you don\'t have the right to modify this user');
        }

        if(!empty($request->avatar) && !str_starts_with($request->avatar, '/storage')){
            $decoded = base64_decode($request->avatar);
            $file = 'avatar';
            file_put_contents($file, $decoded);
            $user->avatar = Storage::url(Storage::disk('public')->putFile('avatars', $file));
        } elseif (!empty($request->avatar)) {
            $user->avatar = $request->avatar;
        } else {
            $user->avatar = null;
        }

        $user->email = $request->email ?? $user->email;
        $user->firstname = $request->firstname ?? $user->firstname;
        $user->lastname = $request->lastname ?? $user->lastname;
        $user->address1 = $request->address1 ?? $user->address1;
        $user->address2 = $request->address2 ?? $user->address2;
        $user->zipCode = $request->zipCode ?? $user->zipCode;
        $user->city = $request->city ?? $user->city;
        $user->primaryPhone = $request->primaryPhone ?? $user->primaryPhone;
        $user->secondaryPhone = $request->secondaryPhone ?? $user->secondaryPhone;
        $user->save();

        return $this->sendResponse(UserResource::make($user), 'User updated successfully.');
    }

    /**
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $user = User::find($id);

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        $user->disabled_at = date('Y-m-d H:i:s');
        $user->save();

        return $this->sendResponse(UserResource::make($user), 'User disabled successfully.');
    }

    /**
     * @param $user
     * @return bool
     */
    public function check_owner($user): bool
    {
        if (Auth::user()->hasRole(['citizen', 'moderator'])) {
            if ($user->id != Auth::user()->getAuthIdentifier()) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function UserValidator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users|nullable',
            'firstname' => 'string|min:2|max:55|nullable',
            'lastname' => 'string|min:2|max:55|nullable',
            'address1' => 'string|min:2|max:255|nullable',
            'address2' => 'string|min:2|max:255|nullable',
            'zipCode' => 'string|min:2|max:20|nullable',
            'city' => 'string|min:2|max:55|nullable',
            'primaryPhone' => 'string|nullable',
            'secondaryPhone' => 'string|nullable',
            'birthDate' => 'date|nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }
    }
}
