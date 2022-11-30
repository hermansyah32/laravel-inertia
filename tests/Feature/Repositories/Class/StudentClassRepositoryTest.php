<?php

namespace Tests\Feature\Repositories\Class;

use App\Http\Repositories\StudentClassRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class StudentClassRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_class_index_repository()
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

    public function test_student_class_index_with_search_repository()
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

    public function test_student_class_index_trashed_repository()
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

    public function test_student_class_index_trashed_with_search_repository()
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

    public function test_student_class_create_repository()
    {
        $input = [
            'name' => 'Class ' . fake()->randomLetter(),
            'student_grade_id' => fake()->uuid(),
            'student_department_id' => fake()->uuid()
        ];

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentClassRepository $repository) use ($input) {
            return $repository->create($input);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($input['name'], $result->getBodyData()->name);
    }

    public function test_student_class_get_repository()
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

    public function test_student_class_get_trashed_repository()
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

    public function test_student_class_update_repository()
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

    public function test_student_class_restore_repository()
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

    public function test_student_class_delete_repository()
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

    public function test_student_class_delete_permanently_repository()
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

    public function test_student_class_assign_student_repository()
    {
        // Generate Student
        $students = Student::factory(10)->create();
        $studentIds = [];
        foreach ($students as $student) {
            $studentIds[] = $student->id;
        }

        // Generate student class
        $studentClass = StudentClass::factory()->create();

        /** @var BodyResponse $delete */
        $assign = App::call(function (StudentClassRepository $repository) use ($studentClass, $studentIds) {
            return $repository->assignStudents(['studentId' => $studentIds], $studentClass->id);
        });

        $dataFind = Student::all();
        $isStudentClassChanged = true;
        foreach ($dataFind as $student) {
            if ($student->student_class_id === null) {
                $isStudentClassChanged = false;
                break;
            }
        }

        $this->assertInstanceOf(BodyResponse::class, $assign);
        $this->assertSame($assign->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertTrue($isStudentClassChanged);
    }
}
