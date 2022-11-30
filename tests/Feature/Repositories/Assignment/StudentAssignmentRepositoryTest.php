<?php

namespace Tests\Feature\Repositories\Assignment;

use App\Http\Repositories\StudentAssignmentRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\StudentAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class StudentAssignmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_assignments_index_repository()
    {
        $studentAssignment = StudentAssignment::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentAssignmentRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_student_assignments_index_with_search_repository()
    {
        $studentAssignment = StudentAssignment::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentAssignmentRepository $repository) use ($studentAssignment) {
            return $repository->index('desc', ['col' => ['user_id'], 'comp' => ['eq'], 'val' => $studentAssignment->user_id]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_student_assignments_index_trashed_repository()
    {
        $studentAssignment = StudentAssignment::factory()->create();
        $studentAssignment->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentAssignmentRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_student_assignments_index_trashed_with_search_repository()
    {
        $studentAssignment = StudentAssignment::factory()->create();
        $studentAssignment->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentAssignmentRepository $repository) use ($studentAssignment) {
            return $repository->indexTrashed('desc', ['col' => ['user_id'], 'comp' => ['eq'], 'val' => $studentAssignment->user_id]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_student_assignments_create_repository()
    {
        $studentAssignment = [
            'user_id' => fake()->uuid(),
            'assignment_group_id' => fake()->uuid(),
            'subject_assignment_id' => fake()->uuid(),
            'answer' => fake()->randomLetter(),
        ];

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentAssignmentRepository $repository) use ($studentAssignment) {
            return $repository->create($studentAssignment);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentAssignment['user_id'], $result->getBodyData()->user_id);
    }

    public function test_student_assignments_get_repository()
    {
        $studentAssignment = StudentAssignment::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentAssignmentRepository $repository) use ($studentAssignment) {
            return $repository->get('id', $studentAssignment->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentAssignment->user_id, $result->getBodyData()->user_id);
    }

    public function test_student_assignments_get_trashed_repository()
    {
        $studentAssignment = StudentAssignment::factory()->create();
        $studentAssignment->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentAssignmentRepository $repository) use ($studentAssignment) {
            return $repository->getTrashed('id', $studentAssignment->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentAssignment->user_id, $result->getBodyData()->user_id);
    }

    public function test_student_assignments_update_repository()
    {
        $studentAssignment = StudentAssignment::factory()->create();
        $updatedData = $studentAssignment->toArray();
        $updatedData['user_id'] = fake()->uuid();

        /** @var BodyResponse $result */
        $result =  App::call(function (StudentAssignmentRepository $repository) use ($studentAssignment, $updatedData) {
            return $repository->updateBy($updatedData, 'id', $studentAssignment->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedData['user_id'], $result->getBodyData()->user_id);
    }

    public function test_student_assignments_restore_repository()
    {
        $studentAssignment = StudentAssignment::factory()->create();
        $studentAssignment->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (StudentAssignmentRepository $repository) use ($studentAssignment) {
            return $repository->restoreBy('id', $studentAssignment->id);
        });

        $dataFind = StudentAssignment::where('id', $studentAssignment->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($studentAssignment->user_id, $dataFind->user_id);
    }

    public function test_student_assignments_delete_repository()
    {
        $studentAssignment = StudentAssignment::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (StudentAssignmentRepository $repository) use ($studentAssignment) {
            return $repository->deleteBy('id', $studentAssignment->id);
        });

        $dataFind = StudentAssignment::where('id', $studentAssignment->id)->first();
        $dataDeleted = StudentAssignment::onlyTrashed()->where('id', $studentAssignment->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertSame($dataDeleted->user_id, $studentAssignment->user_id);
    }

    public function test_student_assignments_delete_permanently_repository()
    {
        $studentAssignment = StudentAssignment::factory()->create();
        $studentAssignment->delete();

        /** @var BodyResponse $delete */
        $delete = App::call(function (StudentAssignmentRepository $repository) use ($studentAssignment) {
            return $repository->permanentDeleteBy('id', $studentAssignment->id);
        });

        $dataFind = StudentAssignment::where('id', $studentAssignment->id)->first();
        $dataDeleted = StudentAssignment::onlyTrashed()->where('id', $studentAssignment->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertNull($dataDeleted);
    }
}
