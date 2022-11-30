<?php

namespace Tests\Feature\Repositories\Class;

use App\Http\Repositories\StudentDepartmentRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\StudentDepartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class StudentDepartmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_departments_index_repository()
    {
        $studentDepartment = StudentDepartment::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentDepartmentRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_student_departments_index_with_search_repository()
    {
        $studentDepartment = StudentDepartment::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentDepartmentRepository $repository) use ($studentDepartment) {
            return $repository->index('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $studentDepartment->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_student_departments_index_trashed_repository()
    {
        $studentDepartment = StudentDepartment::factory()->create();
        $studentDepartment->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentDepartmentRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_student_departments_index_trashed_with_search_repository()
    {
        $studentDepartment = StudentDepartment::factory()->create();
        $studentDepartment->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentDepartmentRepository $repository) use ($studentDepartment) {
            return $repository->indexTrashed('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $studentDepartment->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_student_departments_create_repository()
    {
        $input = [
            'name' => 'Department ' . fake()->randomLetter(),
        ];

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentDepartmentRepository $repository) use ($input) {
            return $repository->create($input);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($input['name'], $result->getBodyData()->name);
    }

    public function test_student_departments_get_repository()
    {
        $studentDepartment = StudentDepartment::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentDepartmentRepository $repository) use ($studentDepartment) {
            return $repository->get('id', $studentDepartment->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentDepartment->name, $result->getBodyData()->name);
    }

    public function test_student_departments_get_trashed_repository()
    {
        $studentDepartment = StudentDepartment::factory()->create();
        $studentDepartment->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentDepartmentRepository $repository) use ($studentDepartment) {
            return $repository->getTrashed('id', $studentDepartment->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentDepartment->name, $result->getBodyData()->name);
    }

    public function test_student_departments_update_repository()
    {
        $studentDepartment = StudentDepartment::factory()->create();
        $updatedData = $studentDepartment->toArray();
        $updatedData['name'] = 'StudentDepartment A Edited';

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentDepartmentRepository $repository) use ($studentDepartment, $updatedData) {
            return $repository->updateBy($updatedData, 'id', $studentDepartment->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedData['name'], $result->getBodyData()->name);
    }

    public function test_student_departments_restore_repository()
    {
        $studentDepartment = StudentDepartment::factory()->create();
        $studentDepartment->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (StudentDepartmentRepository $repository) use ($studentDepartment) {
            return $repository->restoreBy('id', $studentDepartment->id);
        });

        $dataFind = StudentDepartment::where('id', $studentDepartment->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentDepartment->name, $dataFind->name);
    }

    public function test_student_departments_delete_repository()
    {
        $studentDepartment = StudentDepartment::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (StudentDepartmentRepository $repository) use ($studentDepartment) {
            return $repository->deleteBy('id', $studentDepartment->id);
        });

        $dataFind = StudentDepartment::where('id', $studentDepartment->id)->first();
        $dataDeleted = StudentDepartment::onlyTrashed()->where('id', $studentDepartment->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertSame($dataDeleted->name, $studentDepartment->name);
    }

    public function test_student_departments_delete_permanently_repository()
    {
        $studentDepartment = StudentDepartment::factory()->create();
        $studentDepartment->delete();

        /** @var BodyResponse $delete */
        $delete = App::call(function (StudentDepartmentRepository $repository) use ($studentDepartment) {
            return $repository->permanentDeleteBy('id', $studentDepartment->id);
        });

        $dataFind = StudentDepartment::where('id', $studentDepartment->id)->first();
        $dataDeleted = StudentDepartment::onlyTrashed()->where('id', $studentDepartment->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertNull($dataDeleted);
    }
}
