<?php

namespace Tests\Feature\Repositories\Assignment;

use App\Http\Repositories\SubjectAssignmentRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\SubjectAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SubjectAssignmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_subject_assignments_index_repository()
    {
        $subjectAssignment = SubjectAssignment::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectAssignmentRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_subject_assignments_index_with_search_repository()
    {
        $subjectAssignment = SubjectAssignment::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectAssignmentRepository $repository) use ($subjectAssignment) {
            return $repository->index('desc', ['col' => ['type'], 'comp' => ['eq'], 'val' => $subjectAssignment->type]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_subject_assignments_index_trashed_repository()
    {
        $subjectAssignment = SubjectAssignment::factory()->create();
        $subjectAssignment->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectAssignmentRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_subject_assignments_index_trashed_with_search_repository()
    {
        $subjectAssignment = SubjectAssignment::factory()->create();
        $subjectAssignment->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectAssignmentRepository $repository) use ($subjectAssignment) {
            return $repository->indexTrashed('desc', ['col' => ['type'], 'comp' => ['eq'], 'val' => $subjectAssignment->type]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_subject_assignments_create_repository()
    {
        $subjectAssignment = [
            'assignment_group_id' => fake()->uuid(),
            'type' => 'multiple-choice',
            'question' => 'Question ' . fake()->randomLetter(),
            'options' => json_encode(['A' => 'True', 'B' => 'False', 'C' => 'False', 'D' => 'False', 'E' => 'False',]),
            'answer' => 'A',
            'score' => fake()->randomNumber()
        ];

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectAssignmentRepository $repository) use ($subjectAssignment) {
            return $repository->create($subjectAssignment);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectAssignment['type'], $result->getBodyData()->type);
    }

    public function test_subject_assignments_get_repository()
    {
        $subjectAssignment = SubjectAssignment::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectAssignmentRepository $repository) use ($subjectAssignment) {
            return $repository->get('id', $subjectAssignment->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectAssignment->type, $result->getBodyData()->type);
    }

    public function test_subject_assignments_get_trashed_repository()
    {
        $subjectAssignment = SubjectAssignment::factory()->create();
        $subjectAssignment->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectAssignmentRepository $repository) use ($subjectAssignment) {
            return $repository->getTrashed('id', $subjectAssignment->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectAssignment->type, $result->getBodyData()->type);
    }

    public function test_subject_assignments_update_repository()
    {
        $subjectAssignment = SubjectAssignment::factory()->create();
        $updatedData = $subjectAssignment->toArray();
        $updatedData['type'] = 'SubjectAssignment A Edited';

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectAssignmentRepository $repository) use ($subjectAssignment, $updatedData) {
            return $repository->updateBy($updatedData, 'id', $subjectAssignment->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedData['type'], $result->getBodyData()->type);
    }

    public function test_subject_assignments_restore_repository()
    {
        $subjectAssignment = SubjectAssignment::factory()->create();
        $subjectAssignment->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (SubjectAssignmentRepository $repository) use ($subjectAssignment) {
            return $repository->restoreBy('id', $subjectAssignment->id);
        });

        $dataFind = SubjectAssignment::where('id', $subjectAssignment->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subjectAssignment->type, $dataFind->type);
    }

    public function test_subject_assignments_delete_repository()
    {
        $subjectAssignment = SubjectAssignment::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubjectAssignmentRepository $repository) use ($subjectAssignment) {
            return $repository->deleteBy('id', $subjectAssignment->id);
        });

        $dataFind = SubjectAssignment::where('id', $subjectAssignment->id)->first();
        $dataDeleted = SubjectAssignment::onlyTrashed()->where('id', $subjectAssignment->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertSame($dataDeleted->type, $subjectAssignment->type);
    }

    public function test_subject_assignments_delete_permanently_repository()
    {
        $subjectAssignment = SubjectAssignment::factory()->create();
        $subjectAssignment->delete();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubjectAssignmentRepository $repository) use ($subjectAssignment) {
            return $repository->permanentDeleteBy('id', $subjectAssignment->id);
        });

        $dataFind = SubjectAssignment::where('id', $subjectAssignment->id)->first();
        $dataDeleted = SubjectAssignment::onlyTrashed()->where('id', $subjectAssignment->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertNull($dataDeleted);
    }
}
