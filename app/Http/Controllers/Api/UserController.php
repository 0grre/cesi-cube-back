<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(User::all())->withCookie('success', 'Users found successfully.');;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json(self::UserValidator($request))->withCookie('success', 'User created successfully.');
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

        return response()->json($user)->withCookie('success', 'User found successfully.');
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
        return response()->json(self::UserValidator($request, $id));
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

        return response()->json($user)->withCookie('success', 'User deleted successfully.');
    }

    /**
     * @param Request $request
     * @param null $id
     * @return User|JsonResponse
     */
    public function UserValidator(Request $request, $id = null): User|JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique',
            'password' => 'required|string',
            'firstname' => 'string|min:2|max:55',
            'lastname' => 'string|min:2|max:55',
            'address1' => 'string|min:2|max:255',
            'address2' => 'string|min:2|max:255',
            'zipCode' => 'regex:\d{5}',
            'city' => 'string|min:2|max:55',
            'primaryPhone' => 'string',
            'secondaryPhone' => 'string',
            'birthDate' => 'date',
            'disabledAt' => 'date',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $user = $id ? User::find($id) : new User();
        $user->email = $request->email;
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
