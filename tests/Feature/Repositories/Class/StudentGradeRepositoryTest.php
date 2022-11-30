<?php

namespace Tests\Feature\Repositories\Class;

use App\Http\Repositories\StudentGradeRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\StudentGrade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class StudentGradeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_grades_index_repository()
    {
        $studentGrade = StudentGrade::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentGradeRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_student_grades_index_with_search_repository()
    {
        $studentGrade = StudentGrade::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentGradeRepository $repository) use ($studentGrade) {
            return $repository->index('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $studentGrade->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_student_grades_index_trashed_repository()
    {
        $studentGrade = StudentGrade::factory()->create();
        $studentGrade->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentGradeRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_student_grades_index_trashed_with_search_repository()
    {
        $studentGrade = StudentGrade::factory()->create();
        $studentGrade->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentGradeRepository $repository) use ($studentGrade) {
            return $repository->indexTrashed('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $studentGrade->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_student_grades_create_repository()
    {
        $input = [
            'name' => 'Department ' . fake()->randomLetter(),
        ];

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentGradeRepository $repository) use ($input) {
            return $repository->create($input);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($input['name'], $result->getBodyData()->name);
    }

    public function test_student_grades_get_repository()
    {
        $studentGrade = StudentGrade::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentGradeRepository $repository) use ($studentGrade) {
            return $repository->get('id', $studentGrade->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentGrade->name, $result->getBodyData()->name);
    }

    public function test_student_grades_get_trashed_repository()
    {
        $studentGrade = StudentGrade::factory()->create();
        $studentGrade->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentGradeRepository $repository) use ($studentGrade) {
            return $repository->getTrashed('id', $studentGrade->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentGrade->name, $result->getBodyData()->name);
    }

    public function test_student_grades_update_repository()
    {
        $studentGrade = StudentGrade::factory()->create();
        $updatedData = $studentGrade->toArray();
        $updatedData['name'] = 'StudentGrade A Edited';

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentGradeRepository $repository) use ($studentGrade, $updatedData) {
            return $repository->updateBy($updatedData, 'id', $studentGrade->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedData['name'], $result->getBodyData()->name);
    }

    public function test_student_grades_restore_repository()
    {
        $studentGrade = StudentGrade::factory()->create();
        $studentGrade->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (StudentGradeRepository $repository) use ($studentGrade) {
            return $repository->restoreBy('id', $studentGrade->id);
        });

        $dataFind = StudentGrade::where('id', $studentGrade->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentGrade->name, $dataFind->name);
    }

    public function test_student_grades_delete_repository()
    {
        $studentGrade = StudentGrade::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (StudentGradeRepository $repository) use ($studentGrade) {
            return $repository->deleteBy('id', $studentGrade->id);
        });

        $dataFind = StudentGrade::where('id', $studentGrade->id)->first();
        $dataDeleted = StudentGrade::onlyTrashed()->where('id', $studentGrade->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertSame($dataDeleted->name, $studentGrade->name);
    }

    public function test_student_grades_delete_permanently_repository()
    {
        $studentGrade = StudentGrade::factory()->create();
        $studentGrade->delete();

        /** @var BodyResponse $delete */
        $delete = App::call(function (StudentGradeRepository $repository) use ($studentGrade) {
            return $repository->permanentDeleteBy('id', $studentGrade->id);
        });

        $dataFind = StudentGrade::where('id', $studentGrade->id)->first();
        $dataDeleted = StudentGrade::onlyTrashed()->where('id', $studentGrade->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertNull($dataDeleted);
    }
}
