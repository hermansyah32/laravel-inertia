<?php

namespace Tests\Feature\Repositories;

use App\Http\Repositories\UserRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_index_repository()
    {
        $user = User::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (UserRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_users_index_with_search_repository()
    {
        $user = User::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (UserRepository $repository) use ($user) {
            return $repository->index('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $user->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_users_index_trashed_repository()
    {
        $user = User::factory()->create();
        $user->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (UserRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_users_index_trashed_with_search_repository()
    {
        $user = User::factory()->create();
        $user->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (UserRepository $repository) use ($user) {
            return $repository->indexTrashed('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $user->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_users_get_repository()
    {
        $user = User::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (UserRepository $repository) use ($user) {
            return $repository->get('id', $user->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($user->name, $result->getBodyData()->name);
    }

    public function test_users_get_trashed_repository()
    {
        $user = User::factory()->create();
        $user->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (UserRepository $repository) use ($user) {
            return $repository->getTrashed('id', $user->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($user->name, $result->getBodyData()->name);
    }

    public function test_users_update_repository()
    {
        $user = User::factory()->create();
        $updatedUser = [
            'name' => 'Ameera Raimkar'
        ];

        /** @var BodyResponse $result */
        $result =  App::call(function (UserRepository $repository) use ($user, $updatedUser) {
            return $repository->updateBy($updatedUser, 'id', $user->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedUser['name'], $result->getBodyData()->name);
    }

    public function test_users_restore_repository()
    {
        $user = User::factory()->create();
        $user->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (UserRepository $repository) use ($user) {
            return $repository->restoreBy('id', $user->id);
        });

        $userFind = User::where('id', $user->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($user->name, $userFind->name);
    }

    public function test_users_delete_repository()
    {
        $user = User::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (UserRepository $repository) use ($user) {
            return $repository->deleteBy('id', $user->id);
        });

        $userFind = User::where('id', $user->id)->first();
        $userDeleted = User::onlyTrashed()->where('id', $user->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($userFind);
        $this->assertSame($userDeleted->name, $user->name);
    }

    public function test_users_delete_permanently_repository()
    {
        $user = User::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (UserRepository $repository) use ($user) {
            return $repository->permanentDeleteBy('id', $user->id);
        });

        $userFind = User::where('id', $user->id)->first();
        $userDeleted = User::onlyTrashed()->where('id', $user->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($userFind);
        $this->assertNull($userDeleted);
    }

    public function test_users_reset_repository()
    {
        $this->markTestIncomplete('Reset password already done in database, but new random password should be send to user via email. Email notification for new password is not complete yet');

        $user = User::factory()->create();
        $isPassword = Hash::check('password', $user->password);

        /** @var BodyResponse $delete */
        $result = App::call(function (UserRepository $repository) use ($user) {
            return $repository->reset('id', $user->id);
        });

        $userFind = User::where('id', $user->id)->first();
        $checkNewPassword = Hash::check('password', $userFind->password);

        $this->assertTrue($isPassword);
        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertFalse($checkNewPassword);
    }
}
