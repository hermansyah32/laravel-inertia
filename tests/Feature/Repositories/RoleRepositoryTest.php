<?php

namespace Tests\Feature\Repositories;

use App\Http\Repositories\RoleRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class RoleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_index_repository()
    {
        $role = Role::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (RoleRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_roles_index_with_search_repository()
    {
        $role = Role::factory()->create();
        $role->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (RoleRepository $repository) use ($role) {
            return $repository->index('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $role->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_roles_index_trashed_repository()
    {
        $role = Role::factory()->create();
        $role->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (RoleRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_roles_index_trashed_with_search_repository()
    {
        $role = Role::factory()->create();
        $role->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (RoleRepository $repository) use ($role) {
            return $repository->indexTrashed('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $role->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_roles_get_repository()
    {
        $role = Role::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (RoleRepository $repository) use ($role) {
            return $repository->get('id', $role->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($role->name, $result->getBodyData()->name);
    }

    public function test_roles_get_trashed_repository()
    {
        $role = Role::factory()->create();
        $role->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (RoleRepository $repository) use ($role) {
            return $repository->getTrashed('id', $role->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($role->name, $result->getBodyData()->name);
    }

    public function test_roles_update_repository()
    {
        $role = Role::factory()->create();
        $updatedUser = [
            'name' => 'Role A'
        ];

        /** @var BodyResponse $result */
        $result =  App::call(function (RoleRepository $repository) use ($role, $updatedUser) {
            return $repository->updateBy($updatedUser, 'id', $role->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedUser['name'], $result->getBodyData()->name);
    }

    public function test_roles_restore_repository()
    {
        $role = Role::factory()->create();
        $role->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (RoleRepository $repository) use ($role) {
            return $repository->restoreBy('id', $role->id);
        });

        $roleFind = Role::where('id', $role->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($role->name, $roleFind->name);
    }

    public function test_roles_delete_repository()
    {
        $role = Role::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (RoleRepository $repository) use ($role) {
            return $repository->deleteBy('id', $role->id);
        });

        $roleFind = Role::where('id', $role->id)->first();
        $roleDeleted = Role::onlyTrashed()->where('id', $role->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($roleFind);
        $this->assertSame($roleDeleted->name, $role->name);
    }

    public function test_roles_delete_permanently_repository()
    {
        $role = Role::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (RoleRepository $repository) use ($role) {
            return $repository->permanentDeleteBy('id', $role->id);
        });

        $roleFind = Role::where('id', $role->id)->first();
        $roleDeleted = Role::onlyTrashed()->where('id', $role->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($roleFind);
        $this->assertNull($roleDeleted);
    }
}
