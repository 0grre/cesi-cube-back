<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        return $this->sendResponse(DB::table('users')->paginate(10), 'Users found successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
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

        return $this->sendResponse($user, 'User found successfully.');
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
        $user = $id ? User::find($id) : new User();

        $decoded = base64_decode($request->avatar);
        $file = '/avatar';
        file_put_contents($file, $decoded);

        $user->email = $request->email ?? $user->email;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
        $user->avatar = $request->avatar ? Storage::url(Storage::disk('public')->putFile('medias', $file)) : $user->avatar;
        $user->firstname = $request->firstname ?? $user->firstname;
        $user->lastname = $request->lastname ?? $user->lastname;
        $user->address1 = $request->address1 ?? $user->address1;
        $user->address2 = $request->address2 ?? $user->address2;
        $user->zipCode = $request->zipCode ?? $user->zipCode;
        $user->city = $request->city ?? $user->city;
        $user->primaryPhone = $request->primaryPhone ?? $user->primaryPhone;
        $user->secondaryPhone = $request->secondaryPhone ?? $user->secondaryPhone;
        $user->birthDate = $request->birthDate ?? $user->birthDate;
        $user->save();

        return $this->sendResponse($user, 'User updated successfully.');
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

        return $this->sendResponse($user, 'User disabled successfully.');
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function UserValidator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users|nullable',
            'password' => 'string|min:4|nullable',
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
