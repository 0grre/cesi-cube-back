<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        return $this->sendResponse(User::all(), 'Users found successfully.');;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->sendResponse(self::UserValidator($request), 'User created successfully.');
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
        return $this->sendResponse(self::UserValidator($request, $id), 'User updated successfully.');
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

        return $this->sendResponse($user, 'User disabled successfully.');
    }

    /**
     * @param Request $request
     * @param null $id
     * @return User|JsonResponse
     */
    public function UserValidator(Request $request, $id = null): User|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique',
            'password' => 'string|min:4',
            'firstname' => 'string|min:2|max:55',
            'lastname' => 'string|min:2|max:55',
            'address1' => 'string|min:2|max:255',
            'address2' => 'string|min:2|max:255',
            'zipCode' => 'integer',
            'city' => 'string|min:2|max:55',
            'primaryPhone' => 'string',
            'secondaryPhone' => 'string',
            'birthDate' => 'date',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $user = $id ? User::find($id) : new User();

        if($request->email) {
            $user->email = $request->email;
        }
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        if(!empty($request->avatar)){
            $user->avatar = Storage::url(Storage::disk('public')->put('medias', $request->avatar));
        }
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address1 = $request->address1;
        $user->address2 = $request->address2;
        $user->zipCode = $request->zipCode;
        $user->city = $request->city;
        $user->primaryPhone = $request->primaryPhone;
        $user->secondaryPhone = $request->secondaryPhone;
        $user->birthDate = $request->birthDate;
        $user->save();

        return $user;
    }
}
