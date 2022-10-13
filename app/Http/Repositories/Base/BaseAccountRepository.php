<?php

namespace App\Http\Repositories\Base;

use App\Http\Response\BodyResponse;
use App\Http\Response\MessageResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

abstract class BaseAccountRepository
{
    /**
     * @var date format
     */
    const CommonDateFormat = "Y-m-d";

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var perPage
     */
    protected $perPage;

    /**
     * Message response key
     * @var string
     */
    protected $messageResponseKey = 'Data';

    /**
     * Message response init
     * @var object messageResponse
     */
    protected $messageResponse;

    /**
     * Base repository constructor
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->messageResponseKey = Lang::get('data.profile');
        $this->messageResponse = MessageResponse::getMessage($this->messageResponseKey);
    }

    /**
     * Update message response property
     * @param array $messageResponse 
     * @param bool $isPatch 
     * @return void 
     */
    public function refreshMessageResponse($messageResponse, $isPatch = true)
    {
        if ($isPatch) {
            foreach ($messageResponse as $key => $value) {
                $this->messageResponse[$key] = $value;
            }
        } else {
            $this->messageResponse = $messageResponse;
        }
    }

    /**
     * Get authenticated user
     * @return Authenticatable 
     */
    protected function currentAccount(): Authenticatable
    {
        if (is_null(Auth::user())) throw new AuthenticationException;
        return Auth::user();
    }

    /**
     * Get current active guard name.
     * @return string 
     */
    protected function currentGuard(): string
    {
        if (is_null(Auth::user())) throw new AuthenticationException;
        return Auth::getDefaultDriver();
    }

    /**
     * Get authenticated user profile
     * @return BodyResponse 
     */
    protected function getProfile(): BodyResponse
    {
        $account = $this->currentAccount();
        $account->Profile;
        $body = new BodyResponse();

        if (empty($account)) {
            $body->setResponseNotFound($this->messageResponseKey);
            return $body;
        }

        $body->setBodyData($account);
        return $body;
    }

    /**
     * Update authenticated user data except password
     * @return BodyResponse 
     */
    protected function updateAccount(string $email, string $username): BodyResponse
    {
        $data = ['email' => $email, 'username' => $username];
        $validator = Validator::make($data, $this->AccountRules());
        if ($validator->fails()) {
            $body = new BodyResponse();
            $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);
            return $body;
        }

        $account = Auth::user();
        $account->fill($data);
        $account->save();
        $body = $this->getProfile();
        $body->setBodyMessage($this->messageResponse['successUpdated']);
        return $body;
    }

    /**
     * Update authenticated user profile
     * @return BodyResponse 
     */
    protected function updateProfile(array $data): BodyResponse
    {
        $validator = Validator::make($data, $this->ProfileRules());
        if ($validator->fails()) {
            $body = new BodyResponse();
            $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);
            return $body;
        }

        if (array_key_exists('profile_photo_url', $data)) {
            $older_file = DB::table('user_profiles')->where('user_id', Auth::user()->id)->first(['photo_url'])->photo_url;
            $isDeleted = Storage::disk('public')->delete($older_file);
            if (!$isDeleted) {
                // TODO: if not deleted create report
            }

            $filename = 'profile' . '_' . md5(time()) . '.' . $data['profile_photo_url']->getClientOriginalExtension();
            $isUploaded = Storage::disk('public')->put('user/avatar/' . $filename, File::get($data['profile_photo_url']->getRealPath()));
            if ($isUploaded) $data['profile_photo_url'] = 'user/avatar/' . $filename;
        }

        $account = $this->currentAccount();
        $account->fill($data);
        $account->Profile->fill($this->filterProfileData($data));
        $account->save();

        $body = $this->getProfile();
        $body->setBodyMessage($this->messageResponse['successUpdated']);
        return $body;
    }

    /**
     * Update authenticated user password
     * @return BodyResponse 
     */
    protected function updatePassword(array $data): BodyResponse
    {
        $validator =  Validator::make($data, $this->PasswordRules());
        if ($validator->fails()) {
            $body = new BodyResponse();
            $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);
            return $body;
        }
        $account = $this->currentAccount();

        $body = new BodyResponse();
        if (!Hash::check($data['current_password'], $account->password)) {
            $body->setResponseAuthFailed();
            return $body;
        }
        $data['password'] = bcrypt($data['password']);

        $account->password = $data['password'];
        $account->save();

        $body = new BodyResponse();
        $body->setBodyMessage($this->messageResponse['successUpdated']);
        return $body;
    }

    /**
     * Remove profile name prefix from input key name
     * @param array $input Request input array
     * @return array 
     */
    private function filterProfileData(array $input): array
    {
        $result = [];
        foreach ($input as $itemkey => $itemvalue) {
            $result[str_replace('profile_', '', $itemkey)] = $itemvalue;
        }
        return $result;
    }


    protected function ProfileRules(): array
    {
        return [
            'name' => 'required',
            'profile_gender' => ['nullable', 'string', 'in:male,female'],
            'profile_birthday' => 'nullable|date_format:' . self::CommonDateFormat,
            'profile_address' => 'nullable',
            'profile_photo_url' => 'nullable|file|max:5120|mimes:jpg,png,jpeg',
            'profile_phone' => 'nullable',
        ];
    }

    protected function AccountRules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:super_admins,email', 'unique:admins,email', 'unique:users,email'],
            'username' => ['required', 'unique:super_admins,username',  'unique:admins,username',  'unique:users,username']
        ];
    }

    protected function PasswordRules(): array
    {
        return [
            'current_password'   => 'required',
            'password'           => 'required|min:8',
            'confirm_password'   => 'required|same:password',
        ];
    }
}
