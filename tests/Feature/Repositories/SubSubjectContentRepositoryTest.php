<?php

namespace Tests\Feature\Repositories;

use App\Http\Repositories\SubSubjectContentRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\SubSubjectContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SubSubjectContentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignment_groups_index_repository()
    {
        $subSubjectContent = SubSubjectContent::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubSubjectContentRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_with_search_repository()
    {
        $subSubjectContent = SubSubjectContent::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubSubjectContentRepository $repository) use ($subSubjectContent) {
            return $repository->index('desc', ['col' => ['title'], 'comp' => ['eq'], 'val' => $subSubjectContent->title]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_index_trashed_repository()
    {
        $subSubjectContent = SubSubjectContent::factory()->create();
        $subSubjectContent->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubSubjectContentRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_trashed_with_search_repository()
    {
        $subSubjectContent = SubSubjectContent::factory()->create();
        $subSubjectContent->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubSubjectContentRepository $repository) use ($subSubjectContent) {
            return $repository->indexTrashed('desc', ['col' => ['title'], 'comp' => ['eq'], 'val' => $subSubjectContent->title]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_get_repository()
    {
        $subSubjectContent = SubSubjectContent::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubSubjectContentRepository $repository) use ($subSubjectContent) {
            return $repository->get('id', $subSubjectContent->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subSubjectContent->title, $result->getBodyData()->title);
    }

    public function test_assignment_groups_get_trashed_repository()
    {
        $subSubjectContent = SubSubjectContent::factory()->create();
        $subSubjectContent->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubSubjectContentRepository $repository) use ($subSubjectContent) {
            return $repository->getTrashed('id', $subSubjectContent->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subSubjectContent->title, $result->getBodyData()->title);
    }

    public function test_assignment_groups_update_repository()
    {
        $subSubjectContent = SubSubjectContent::factory()->create();
        $updatedData = $subSubjectContent->toArray();
        $updatedData['title'] = 'Subject Content A';

        /** @var BodyResponse $result */
        $result =  App::call(function (SubSubjectContentRepository $repository) use ($subSubjectContent, $updatedData) {
            return $repository->updateBy($updatedData, 'id', $subSubjectContent->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedData['title'], $result->getBodyData()->title);
    }

    public function test_assignment_groups_restore_repository()
    {
        $subSubjectContent = SubSubjectContent::factory()->create();
        $subSubjectContent->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (SubSubjectContentRepository $repository) use ($subSubjectContent) {
            return $repository->restoreBy('id', $subSubjectContent->id);
        });

        $dataFind = SubSubjectContent::where('id', $subSubjectContent->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subSubjectContent->title, $dataFind->title);
    }

    public function test_assignment_groups_delete_repository()
    {
        $subSubjectContent = SubSubjectContent::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubSubjectContentRepository $repository) use ($subSubjectContent) {
            return $repository->deleteBy('id', $subSubjectContent->id);
        });

        $dataFind = SubSubjectContent::where('id', $subSubjectContent->id)->first();
        $dataDeleted = SubSubjectContent::onlyTrashed()->where('id', $subSubjectContent->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertSame($dataDeleted->title, $subSubjectContent->title);
    }

    public function test_assignment_groups_delete_permanently_repository()
    {
        $subSubjectContent = SubSubjectContent::factory()->create();
        $subSubjectContent->delete();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubSubjectContentRepository $repository) use ($subSubjectContent) {
            return $repository->permanentDeleteBy('id', $subSubjectContent->id);
        });

        $dataFind = SubSubjectContent::where('id', $subSubjectContent->id)->first();
        $dataDeleted = SubSubjectContent::onlyTrashed()->where('id', $subSubjectContent->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertNull($dataDeleted);
    }
}
