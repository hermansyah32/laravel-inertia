<?php

namespace Tests\Feature\Repositories;

use App\Http\Repositories\ProfileRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\User;
use App\Models\UserProfile;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileRepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_account_get_profile_repository()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        /** @var BodyResponse $result */
        $result =  App::call(function (ProfileRepository $repository) {
            return $repository->getProfile();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($user->name, $result->getBodyData()->name);
        $this->assertArrayHasKey('profile_gender', $result->getBodyData()->toArray());
    }

    public function test_email_update_repository()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $oldEmail = $user->email;
        $newEmail = $this->faker()->email();
        $this->actingAs($user);

        /** @var BodyResponse $result */
        $result =  App::call(function (ProfileRepository $repository) use ($newEmail) {
            return $repository->updateEmail($newEmail, null);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNotEquals($user->email, $oldEmail);
        $this->assertSame($newEmail, $result->getBodyData()->email);
    }

    public function test_account_profile_update_repository()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $oldData = $user->toArray();
        $newData = [
            'name' => $this->faker()->name,
            'profile_gender' => $this->faker->randomElement(['male', 'female'])
        ];

        /** @var BodyResponse $result */
        $result =  App::call(function (ProfileRepository $repository) use ($newData) {
            return $repository->updateProfile($newData);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNotEquals($oldData['name'], $newData['name']);
        $this->assertSame($newData['name'], $result->getBodyData()->name);
    }

    public function test_account_profile_update_with_photo_profile_repository()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake('public');
        $oldData = $user->toArray();
        $newData = [
            'name' => $this->faker()->name,
            'profile_gender' => $this->faker->randomElement(['male', 'female']),
            'profile_photo_url' => $photoProfile = UploadedFile::fake()->image('random.jpg')
        ];

        /** @var BodyResponse $result */
        $result =  App::call(function (ProfileRepository $repository) use ($newData) {
            return $repository->updateProfile($newData);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertEquals('user/' . $user->id . '/avatar/' . $photoProfile->hashName(), $user->fresh()->profile->photo_url);
        Storage::disk('public')->assertExists('user/' . $user->id . '/avatar/' . $photoProfile->hashName());
    }

    public function test_account_password_update_repository()
    {
        $this->markTestIncomplete("When password is changed should send email notification to user");
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $newPassword = $this->faker()->shuffleString('oqwehasl22as');

        /** @var BodyResponse $result */
        $result =  App::call(function (ProfileRepository $repository) use ($newPassword) {
            return $repository->updatePassword([
                'current_password' => 'password', 'password' => $newPassword, 'confirm_password' => $newPassword
            ]);
        });
        dd($result);

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }
}
