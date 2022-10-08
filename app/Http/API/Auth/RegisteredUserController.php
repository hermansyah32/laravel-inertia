<?php

namespace App\Http\API\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Response\BodyResponse;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisteredUserController extends BaseController
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function register(Request $request)
    {
        $body = new BodyResponse();

        $validator = Validator::make($request->only(['name', 'email', 'password', 'username']), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        if ($validator->fails()) {
            $body->setResponseValidationError($validator->errors());
            return $this->sendResponse($body);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $token = $user->createToken($request->string('device_name'))->plainTextToken;
        $body->setBodyData(['user' => $user, 'token' => $token]);

        return $this->sendResponse($body);
    }
}
