<?php

namespace Tests\Feature\Repositories;

use App\Http\Repositories\SubjectGroupRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\SubjectGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SubjectGroupRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignment_groups_index_repository()
    {
        $subjectGroup = SubjectGroup::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectGroupRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_with_search_repository()
    {
        $subjectGroup = SubjectGroup::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectGroupRepository $repository) use ($subjectGroup) {
            return $repository->index('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $subjectGroup->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_index_trashed_repository()
    {
        $subjectGroup = SubjectGroup::factory()->create();
        $subjectGroup->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectGroupRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_trashed_with_search_repository()
    {
        $subjectGroup = SubjectGroup::factory()->create();
        $subjectGroup->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectGroupRepository $repository) use ($subjectGroup) {
            return $repository->indexTrashed('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $subjectGroup->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_get_repository()
    {
        $subjectGroup = SubjectGroup::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectGroupRepository $repository) use ($subjectGroup) {
            return $repository->get('id', $subjectGroup->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectGroup->name, $result->getBodyData()->name);
    }

    public function test_assignment_groups_get_trashed_repository()
    {
        $subjectGroup = SubjectGroup::factory()->create();
        $subjectGroup->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectGroupRepository $repository) use ($subjectGroup) {
            return $repository->getTrashed('id', $subjectGroup->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectGroup->name, $result->getBodyData()->name);
    }

    public function test_assignment_groups_update_repository()
    {
        $subjectGroup = SubjectGroup::factory()->create();
        $updatedData = $subjectGroup->toArray();
        $updatedData['name'] = 'Subject Content A';

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectGroupRepository $repository) use ($subjectGroup, $updatedData) {
            return $repository->updateBy($updatedData, 'id', $subjectGroup->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedData['name'], $result->getBodyData()->name);
    }

    public function test_assignment_groups_restore_repository()
    {
        $subjectGroup = SubjectGroup::factory()->create();
        $subjectGroup->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (SubjectGroupRepository $repository) use ($subjectGroup) {
            return $repository->restoreBy('id', $subjectGroup->id);
        });

        $dataFind = SubjectGroup::where('id', $subjectGroup->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectGroup->name, $dataFind->name);
    }

    public function test_assignment_groups_delete_repository()
    {
        $subjectGroup = SubjectGroup::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubjectGroupRepository $repository) use ($subjectGroup) {
            return $repository->deleteBy('id', $subjectGroup->id);
        });

        $dataFind = SubjectGroup::where('id', $subjectGroup->id)->first();
        $dataDeleted = SubjectGroup::onlyTrashed()->where('id', $subjectGroup->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertSame($dataDeleted->name, $subjectGroup->name);
    }

    public function test_assignment_groups_delete_permanently_repository()
    {
        $subjectGroup = SubjectGroup::factory()->create();
        $subjectGroup->delete();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubjectGroupRepository $repository) use ($subjectGroup) {
            return $repository->permanentDeleteBy('id', $subjectGroup->id);
        });

        $dataFind = SubjectGroup::where('id', $subjectGroup->id)->first();
        $dataDeleted = SubjectGroup::onlyTrashed()->where('id', $subjectGroup->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertNull($dataDeleted);
    }
}
