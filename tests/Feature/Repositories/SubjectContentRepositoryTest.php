<?php

namespace Tests\Feature\Repositories;

use App\Http\Repositories\SubjectContentRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\SubjectContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SubjectContentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignment_groups_index_repository()
    {
        $subjectContent = SubjectContent::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectContentRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_with_search_repository()
    {
        $subjectContent = SubjectContent::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectContentRepository $repository) use ($subjectContent) {
            return $repository->index('desc', ['col' => ['title'], 'comp' => ['eq'], 'val' => $subjectContent->title]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_index_trashed_repository()
    {
        $subjectContent = SubjectContent::factory()->create();
        $subjectContent->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectContentRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_trashed_with_search_repository()
    {
        $subjectContent = SubjectContent::factory()->create();
        $subjectContent->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectContentRepository $repository) use ($subjectContent) {
            return $repository->indexTrashed('desc', ['col' => ['title'], 'comp' => ['eq'], 'val' => $subjectContent->title]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_get_repository()
    {
        $subjectContent = SubjectContent::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectContentRepository $repository) use ($subjectContent) {
            return $repository->get('id', $subjectContent->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectContent->title, $result->getBodyData()->title);
    }

    public function test_assignment_groups_get_trashed_repository()
    {
        $subjectContent = SubjectContent::factory()->create();
        $subjectContent->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectContentRepository $repository) use ($subjectContent) {
            return $repository->getTrashed('id', $subjectContent->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectContent->title, $result->getBodyData()->title);
    }

    public function test_assignment_groups_update_repository()
    {
        $subjectContent = SubjectContent::factory()->create();
        $updatedData = $subjectContent->toArray();
        $updatedData['title'] = 'Subject Content A';

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectContentRepository $repository) use ($subjectContent, $updatedData) {
            return $repository->updateBy($updatedData, 'id', $subjectContent->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedData['title'], $result->getBodyData()->title);
    }

    public function test_assignment_groups_restore_repository()
    {
        $subjectContent = SubjectContent::factory()->create();
        $subjectContent->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (SubjectContentRepository $repository) use ($subjectContent) {
            return $repository->restoreBy('id', $subjectContent->id);
        });

        $dataFind = SubjectContent::where('id', $subjectContent->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectContent->title, $dataFind->title);
    }

    public function test_assignment_groups_delete_repository()
    {
        $subjectContent = SubjectContent::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubjectContentRepository $repository) use ($subjectContent) {
            return $repository->deleteBy('id', $subjectContent->id);
        });

        $dataFind = SubjectContent::where('id', $subjectContent->id)->first();
        $dataDeleted = SubjectContent::onlyTrashed()->where('id', $subjectContent->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertSame($dataDeleted->title, $subjectContent->title);
    }

    public function test_assignment_groups_delete_permanently_repository()
    {
        $subjectContent = SubjectContent::factory()->create();
        $subjectContent->delete();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubjectContentRepository $repository) use ($subjectContent) {
            return $repository->permanentDeleteBy('id', $subjectContent->id);
        });

        $dataFind = SubjectContent::where('id', $subjectContent->id)->first();
        $dataDeleted = SubjectContent::onlyTrashed()->where('id', $subjectContent->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertNull($dataDeleted);
    }
}
