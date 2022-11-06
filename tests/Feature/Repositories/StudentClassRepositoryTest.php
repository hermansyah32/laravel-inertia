<?php

namespace Tests\Feature\Repositories;

use App\Http\Repositories\StudentClassRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\StudentClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class StudentClassRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignment_groups_index_repository()
    {
        $studentClass = StudentClass::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentClassRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_with_search_repository()
    {
        $studentClass = StudentClass::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentClassRepository $repository) use ($studentClass) {
            return $repository->index('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $studentClass->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_index_trashed_repository()
    {
        $studentClass = StudentClass::factory()->create();
        $studentClass->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentClassRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_trashed_with_search_repository()
    {
        $studentClass = StudentClass::factory()->create();
        $studentClass->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentClassRepository $repository) use ($studentClass) {
            return $repository->indexTrashed('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $studentClass->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_get_repository()
    {
        $studentClass = StudentClass::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentClassRepository $repository) use ($studentClass) {
            return $repository->get('id', $studentClass->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentClass->name, $result->getBodyData()->name);
    }

    public function test_assignment_groups_get_trashed_repository()
    {
        $studentClass = StudentClass::factory()->create();
        $studentClass->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentClassRepository $repository) use ($studentClass) {
            return $repository->getTrashed('id', $studentClass->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentClass->name, $result->getBodyData()->name);
    }

    public function test_assignment_groups_update_repository()
    {
        $studentClass = StudentClass::factory()->create();
        $updatedData = $studentClass->toArray();
        $updatedData['name'] = 'StudentClass A Edited';

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentClassRepository $repository) use ($studentClass, $updatedData) {
            return $repository->updateBy($updatedData, 'id', $studentClass->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedData['name'], $result->getBodyData()->name);
    }

    public function test_assignment_groups_restore_repository()
    {
        $studentClass = StudentClass::factory()->create();
        $studentClass->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (StudentClassRepository $repository) use ($studentClass) {
            return $repository->restoreBy('id', $studentClass->id);
        });

        $dataFind = StudentClass::where('id', $studentClass->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentClass->name, $dataFind->name);
    }

    public function test_assignment_groups_delete_repository()
    {
        $studentClass = StudentClass::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (StudentClassRepository $repository) use ($studentClass) {
            return $repository->deleteBy('id', $studentClass->id);
        });

        $dataFind = StudentClass::where('id', $studentClass->id)->first();
        $dataDeleted = StudentClass::onlyTrashed()->where('id', $studentClass->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertSame($dataDeleted->name, $studentClass->name);
    }

    public function test_assignment_groups_delete_permanently_repository()
    {
        $studentClass = StudentClass::factory()->create();
        $studentClass->delete();

        /** @var BodyResponse $delete */
        $delete = App::call(function (StudentClassRepository $repository) use ($studentClass) {
            return $repository->permanentDeleteBy('id', $studentClass->id);
        });

        $dataFind = StudentClass::where('id', $studentClass->id)->first();
        $dataDeleted = StudentClass::onlyTrashed()->where('id', $studentClass->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertNull($dataDeleted);
    }
}
