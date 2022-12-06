<?php

namespace App\Http\Repositories\Base;

use App\Http\Response\BodyResponse;
use App\Http\Response\MessageResponse;
use App\http\Systems\Models\EmailChange;
use App\Models\User;
use App\Models\UserProfile;
use App\Notifications\EmailChangeNotification;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BaseAccountRepository
{
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
     * Token Repository
     * @var TokenRepository
     */
    private $tokenRepository;

    /**
     * Base repository constructor
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->messageResponseKey = Lang::get('data.profile');
        $this->messageResponse = MessageResponse::getMessage($this->messageResponseKey);
        $this->tokenRepository = new TokenRepository(config('app.key'));
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
    public function currentAccount(bool $isBuilder = false): Authenticatable|Model|Builder
    {
        if (!Auth::user()) throw new AuthenticationException();
        $auth = Auth::user();
        if ($isBuilder) return User::where('id', $auth->id);

        $result = User::where('id', $auth->id)->first();
        return $result;
    }

    /**
     * Get all current roles.
     * 
     */
    public function currentRoles()
    {
        if (!Auth::user()) throw new AuthenticationException();
        $auth = Auth::user();
        $result = User::where('id', $auth->id)->with('roles')->first();
        return $result->roles;
    }


    /**
     * Get highest rank current roles.
     * 
     * @return array
     */
    public function currentHighestRole(): array
    {
        if (!Auth::user()) throw new AuthenticationException();
        $auth = Auth::user();
        $result = User::where('id', $auth->id)->with('roles')->whereHas('roles', function ($query) {
            $query->orderBy('rank', 'asc');
        })->first();
        return count($result->roles) > 0
            ? $result->roles[0]->toArray()
            : ['name' => 'unknown', 'rank' => 999, 'permission_tag' => 'unknown'];
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
            $account = $this->currentAccount(true)->with('profile')->with('roles')->first();
            $this->transformProfile($account);
            $this->transformRoles($account);

            $body->setBodyMessage(Lang::get('data.get', ['Data' => 'Profile']));
            $body->setBodyData($account);
        } catch (\Throwable $th) {
            $this->saveLog($th);
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
        }
        return $body;
    }

    /**
     * Send email update link
     * @param string $email New email to be used
     * @return BodyResponse 
     */
    public function sendEmailUpdate(string $email): BodyResponse
    {
        $body = new BodyResponse();
        try {
            $validator = Validator::make(['email' => $email], ['email' => ['required', 'email', 'unique:users']]);
            if ($validator->fails()) return $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);

            $token = $this->tokenRepository->create(Str::reverse($email));
            EmailChange::whereNotNull('created_at')->update(['created_at' => new Date()]);
            EmailChange::create(['email' => $this->currentAccount()->email, 'new_email' => $email, 'token' => $token]);

            Notification::send($this->repository->currentAccount(), new EmailChangeNotification($token, $email));

            $body->setBodyMessage(Lang::get('Email change requested'));
        } catch (\Throwable $th) {
            $this->saveLog($th);
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
        }
        return $body;
    }

    /**
     * Update authenticated user email
     * 
     * @param string $email User email.
     * @return BodyResponse 
     */
    public function updateEmail(string|null $email, string|null $token): BodyResponse
    {
        $body = new BodyResponse();
        try {
            $data = ['email' => $email, 'token' => $token];

            $validator = Validator::make($data, [
                'email' => ['required', 'email', 'unique:users'],
                'token' => ['required', 'string']
            ]);
            if ($validator->fails())
                return $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);

            $emailRequest = EmailChange::where(['email' => $email, 'token' => $token])->first();
            if ($emailRequest === null) return $body->setResponseNotFound('Your request is invalid');

            if (!$this->tokenRepository->verify(Str::reverse($email), $token))
                return $body->setResponseNotFound('Your request can not be verified');

            $account = $this->currentAccount(true)->with('profile')->first();
            $account->email = $emailRequest->new_email;
            $account->save();

            $this->transformProfile($account);

            $body->setBodyData($account);
            $body->setBodyMessage($this->messageResponse['successUpdated']);
        } catch (\Throwable $th) {
            $this->saveLog($th);
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
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
            $account = $this->currentAccount(true)->with('profile')->first();
            if ($account->profile === null) UserProfile::create(['user_id' => $account->id]);

            $validator = Validator::make($data, $account->profile->updateRules());
            if ($validator->fails())
                return $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);

            if (array_key_exists('profile_photo_url', $data) && $data['profile_photo_url']) {
                $olderFile = $account->profile->photo_url;
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

            $this->transformProfile($account);

            $body->setBodyData($account);
            $body->setBodyMessage($this->messageResponse['successUpdated']);
        } catch (\Throwable $th) {
            $this->saveLog($th);
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
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
            if ($validator->fails())
                return $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);

            $account = $this->currentAccount();
            if (!Hash::check($data['current_password'], $account->password)) return $body->setResponseAuthFailed();

            $account->password = bcrypt($data['password']);
            $account->save();

            $body->setBodyMessage($this->messageResponse['successUpdated']);
        } catch (\Throwable $th) {
            $this->saveLog($th);
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
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

    /**
     * Transform account with profile
     * 
     * @param mixed $user User model
     */
    protected function transformProfile($user)
    {
        if ($user->profile === null) return;

        $fillable = $user->profile->getFillable();
        foreach ($fillable as $attr) {
            $user->setAttribute('profile_' . $attr, $user->profile->{$attr});
        }
        unset($user->profile);
    }

    /**
     * Transform account with roles
     * 
     * @param mixed $user User model
     */
    protected function transformRoles(&$user)
    {
        if ($user->roles === null || count($user->roles) < 1) return [];

        $fillable = $user->roles[0]->getFillable();
        $roles = [];
        foreach ($user->roles as $role) {
            $tempRole = [];
            foreach ($fillable as $attr) {
                $tempRole[$attr] = $role[$attr];
            }
            $roles[] = $tempRole;
        }
        $user->setAttribute('roles', $roles);
        unset($user->roles);
    }

    private function saveLog($exception)
    {
        Log::error($exception->getMessage(), ['class', __CLASS__]);
        if (config('app.debug')) dd($exception);
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
