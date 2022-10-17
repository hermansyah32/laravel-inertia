<?php

namespace App\Http\API\Auth;

use App\Http\Controllers\BaseController as Controller;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * Login column type.
     *
     * @var string
     */
    protected $username;

    public function permissionRule()
    {
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @param  Request  $request
     */
    public function login(Request $request)
    {
        $body = new BodyResponse();
        try {
            $this->username = $this->findUsername($request);

            if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
                event(new Lockout($request));
                $body->setResponseError("Too many request", ResponseCode::TOO_MANY_REQUEST);
                return $this->sendResponse($body);
            }

            $validator = Validator::make($request->only([$this->username, 'password', 'device_name']), $this->loginRules());
            if ($validator->fails()) {
                $body->setResponseValidationError($validator->errors());
                return $this->sendResponse($body);
            }

            $user = User::where($this->username, $request->input($this->username))->first();
            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                $body->setResponseAuthFailed();
                return $this->sendResponse($body);
            }

            $token = $user->createToken($request->input('device_name'))->plainTextToken;
            $body->setBodyMessage(Lang::get('data.login'));
            $body->setBodyData(['user' => $user, 'token' => $token]);
            RateLimiter::clear($this->throttleKey($request));
        } catch (\Throwable $th) {
            $body->setResponseError($th->getMessage());
        }

        return $this->sendResponse($body);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  Request $request
     */
    public function logout(Request $request)
    {
        $body = new BodyResponse();
        try {
            $tokenId = $request->bearerToken();
            if ($tokenId)
                $request->user()->currentAccessToken()->delete();

            $body->setBodyMessage(Lang::get('data.logout'));
            $body->setBodyMessage("Logout successfully");
        } catch (\Throwable $th) {
            $body->setResponseError($th->getMessage());
        }
        return $this->sendResponse($body);
    }

    /**
     * Reissue token from valid token.
     *
     * @param  Request $request
     */
    public function reissueToken(Request $request)
    {
        $body = new BodyResponse();
        try {
            $validator = Validator::make($request->all(), ['device_name' => ['required']]);
            if ($validator->fails()) {
                $body->setResponseValidationError($validator->errors(), 'Authentication');
                return $this->sendResponse($body);
            }
            $tokenId = $request->bearerToken();
            if ($tokenId)
                $request->user()->currentAccessToken()->delete();

            $token = $request->user()->createToken($request->device_name)->plainTextToken;
            $body->setBodyMessage(Lang::get('data.reissue_token'));
            $body->setBodyData(['token' => $token]);
        } catch (\Throwable $th) {
            $body->setResponseError($th->getMessage());
        }

        return $this->sendResponse($body);
    }

    /** ============================================== Support Function ========================================== */
    /**
     * Get the login username to be used by the controller.
     * 
     * @param Request $request current request
     * @return string
     */
    public function findUsername($request)
    {
        $login = $request->input('login');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$fieldType => $login]);
        $this->username = $fieldType;
        return $fieldType;
    }

    /**
     * Get login validation rules
     * 
     * @return array<string,string>
     */
    public function loginRules()
    {
        $rules = [
            'password' => ['required', 'string'],
            'device_name' => ['required', 'string']
        ];
        if ($this->username === "email") {
            $rules['email'] = ['required', 'string', 'email'];
        } else {
            $rules['username'] = ['required', 'string',];
        }
        return $rules;
    }

    /**
     * Get the rate limiting throttle key for the request.
     * 
     * @param Request $request
     * @return string
     */
    public function throttleKey($request)
    {
        return Str::transliterate(Str::lower($request->input($this->username)) . '|' . $request->ip());
    }
}
