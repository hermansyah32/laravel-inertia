<?php

namespace Tests\Feature\Repositories;

use App\Http\Repositories\SubjectReferenceRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\SubjectReference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SubjectReferenceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignment_groups_index_repository()
    {
        $subjectReference = SubjectReference::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectReferenceRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_with_search_repository()
    {
        $subjectReference = SubjectReference::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectReferenceRepository $repository) use ($subjectReference) {
            return $repository->index('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $subjectReference->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_index_trashed_repository()
    {
        $subjectReference = SubjectReference::factory()->create();
        $subjectReference->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectReferenceRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_trashed_with_search_repository()
    {
        $subjectReference = SubjectReference::factory()->create();
        $subjectReference->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectReferenceRepository $repository) use ($subjectReference) {
            return $repository->indexTrashed('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $subjectReference->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_get_repository()
    {
        $subjectReference = SubjectReference::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectReferenceRepository $repository) use ($subjectReference) {
            return $repository->get('id', $subjectReference->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectReference->name, $result->getBodyData()->name);
    }

    public function test_assignment_groups_get_trashed_repository()
    {
        $subjectReference = SubjectReference::factory()->create();
        $subjectReference->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectReferenceRepository $repository) use ($subjectReference) {
            return $repository->getTrashed('id', $subjectReference->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectReference->name, $result->getBodyData()->name);
    }

    public function test_assignment_groups_update_repository()
    {
        $subjectReference = SubjectReference::factory()->create();
        $updatedData = $subjectReference->toArray();
        $updatedData['name'] = 'Subject Content A';

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectReferenceRepository $repository) use ($subjectReference, $updatedData) {
            return $repository->updateBy($updatedData, 'id', $subjectReference->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedData['name'], $result->getBodyData()->name);
    }

    public function test_assignment_groups_restore_repository()
    {
        $subjectReference = SubjectReference::factory()->create();
        $subjectReference->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (SubjectReferenceRepository $repository) use ($subjectReference) {
            return $repository->restoreBy('id', $subjectReference->id);
        });

        $dataFind = SubjectReference::where('id', $subjectReference->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectReference->name, $dataFind->name);
    }

    public function test_assignment_groups_delete_repository()
    {
        $subjectReference = SubjectReference::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubjectReferenceRepository $repository) use ($subjectReference) {
            return $repository->deleteBy('id', $subjectReference->id);
        });

        $dataFind = SubjectReference::where('id', $subjectReference->id)->first();
        $dataDeleted = SubjectReference::onlyTrashed()->where('id', $subjectReference->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertSame($dataDeleted->name, $subjectReference->name);
    }

    public function test_assignment_groups_delete_permanently_repository()
    {
        $subjectReference = SubjectReference::factory()->create();
        $subjectReference->delete();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubjectReferenceRepository $repository) use ($subjectReference) {
            return $repository->permanentDeleteBy('id', $subjectReference->id);
        });

        $dataFind = SubjectReference::where('id', $subjectReference->id)->first();
        $dataDeleted = SubjectReference::onlyTrashed()->where('id', $subjectReference->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertNull($dataDeleted);
    }
}
