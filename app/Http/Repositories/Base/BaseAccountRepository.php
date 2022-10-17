<?php

namespace App\Http\Repositories\Base;

use App\Http\Response\BodyResponse;
use App\Http\Response\MessageResponse;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

abstract class BaseAccountRepository
{
    /**
     * @var string CommonDateFormat
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
     * 
     * @param array $messageResponse New message response that replace current message response.
     * @param bool $isPatch If true, than only replace or add message response.
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
     * Get authenticated user.
     * 
     * @return Authenticatable|\Illuminate\Database\Eloquent\Model
     */
    public function currentAccount(): Authenticatable|Model
    {
        if (!Auth::user()) throw new AuthenticationException();
        return Auth::user();
    }

    /**
     * Get current active guard name.
     * 
     * @return string 
     */
    protected function currentGuard(): string
    {
        if (!Auth::user()) throw new AuthenticationException();
        return Auth::getDefaultDriver();
    }

    /**
     * Get authenticated user profile
     * 
     * @return BodyResponse 
     */
    public function getProfile(): BodyResponse
    {
        $body = new BodyResponse();
        try {
            $account = $this->currentAccount();
            if (!$account->has('profile')->exists()) UserProfile::create(['user_id' => $account->id]);

            $body->setBodyData($account->with('profile')->first());
        } catch (\Throwable $th) {
            $body->setResponseError($th->getMessage());
        }
        return $body;
    }

    /**
     * Update authenticated user data except password
     * 
     * @param string $email User email.
     * @param string $user User username.
     * @return BodyResponse 
     */
    public function updateAccount(string $email, string $username): BodyResponse
    {
        $body = new BodyResponse();
        try {
            $data = ['email' => $email, 'username' => $username];
            $validator = Validator::make($data, $this->AccountRules());
            if ($validator->fails()) {
                $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);
                return $body;
            }

            $account = $this->currentAccount();
            $account->fill($data);
            $account->save();

            $body->setBodyData($account);
            $body->setBodyMessage($this->messageResponse['successUpdated']);
        } catch (\Throwable $th) {
            $body->setResponseError($th->getMessage());
        }
        return $body;
    }

    /**
     * Update authenticated user profile
     * 
     * @param array $data User profile input included with user name.
     * @return BodyResponse 
     */
    public function updateProfile(array $data): BodyResponse
    {
        $body = new BodyResponse();
        try {
            $validator = Validator::make($data, $this->ProfileRules());
            if ($validator->fails()) {
                $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);
                return $body;
            }

            $account = $this->currentAccount();
            if (!$account->has('profile')->exists()) UserProfile::create(['user_id' => $account->id]);

            if (array_key_exists('profile_photo_url', $data)) {
                $olderFile = UserProfile::where('user_id', Auth::user()->id)->first(['photo_url'])->photo_url;
                if ($olderFile) Storage::disk('public')->delete($olderFile);

                $fileName = $data['profile_photo_url']->hashName();
                $filePath = 'user/' . $account->id . '/avatar/';
                $isUploaded = Storage::disk('public')
                    ->put($filePath . $fileName, File::get($data['profile_photo_url']->getRealPath()));
                if ($isUploaded) $data['profile_photo_url'] = $filePath . $fileName;
            }

            $account->fill($data);
            $account->save();
            $account->profile->fill($this->filterProfileData($data));
            $account->profile->save();

            $body->setBodyData($account->with('profile')->first());
            $body->setBodyMessage($this->messageResponse['successUpdated']);
        } catch (\Throwable $th) {
            $body->setResponseError($th->getMessage());
        }
        return $body;
    }

    /**
     * Update authenticated user password
     * 
     * @param array $data Input array password has key password, current_password
     * @return BodyResponse 
     */
    public function updatePassword(array $data): BodyResponse
    {
        $body = new BodyResponse();
        try {
            $validator =  Validator::make($data, $this->PasswordRules());
            if ($validator->fails()) {
                $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);
                return $body;
            }

            $account = $this->currentAccount();
            if (!Hash::check($data['current_password'], $account->password)) {
                $body->setResponseAuthFailed();
                return $body;
            }

            $account->password = bcrypt($data['password']);
            $account->save();

            $body->setBodyMessage($this->messageResponse['successUpdated']);
        } catch (\Throwable $th) {
            $body->setResponseError($th->getMessage());
        }
        return $body;
    }

    /** ================================= Support Function Below ================================= */
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

    private function ProfileRules(): array
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

    private function AccountRules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'username' => ['required', 'unique:users,username']
        ];
    }

    private function PasswordRules(): array
    {
        return [
            'current_password'   => 'required',
            'password'           => 'required|min:8',
            'confirm_password'   => 'required|same:password',
        ];
    }
}
