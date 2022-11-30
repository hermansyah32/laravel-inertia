<?php

namespace Tests\Feature\Repositories\Assignment;

use App\Http\Repositories\AssignmentGroupRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\AssignmentGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class AssignmentGroupRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignment_groups_index_repository()
    {
        $assignmentGroup = AssignmentGroup::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (AssignmentGroupRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_with_search_repository()
    {
        $assignmentGroup = AssignmentGroup::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (AssignmentGroupRepository $repository) use ($assignmentGroup) {
            return $repository->index('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $assignmentGroup->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_index_trashed_repository()
    {
        $assignmentGroup = AssignmentGroup::factory()->create();
        $assignmentGroup->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (AssignmentGroupRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_assignment_groups_index_trashed_with_search_repository()
    {
        $assignmentGroup = AssignmentGroup::factory()->create();
        $assignmentGroup->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (AssignmentGroupRepository $repository) use ($assignmentGroup) {
            return $repository->indexTrashed('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $assignmentGroup->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_assignment_groups_get_repository()
    {
        $assignmentGroup = AssignmentGroup::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (AssignmentGroupRepository $repository) use ($assignmentGroup) {
            return $repository->get('id', $assignmentGroup->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($assignmentGroup->name, $result->getBodyData()->name);
    }

    public function test_assignment_groups_create_repository()
    {
        $input = [
            'subject_id' => fake()->uuid(),
            'subject_group_id' => fake()->uuid(),
            'subject_content_id' => fake()->uuid(),
            'name' => 'Assignment Group ' . fake()->randomLetter(),
        ];

        /** @var BodyResponse $result */
        $result =  App::call(function (AssignmentGroupRepository $repository) use ($input) {
            return $repository->create($input);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($input['name'], $result->getBodyData()->name);
    }

    public function test_assignment_groups_get_trashed_repository()
    {
        $assignmentGroup = AssignmentGroup::factory()->create();
        $assignmentGroup->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (AssignmentGroupRepository $repository) use ($assignmentGroup) {
            return $repository->getTrashed('id', $assignmentGroup->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($assignmentGroup->name, $result->getBodyData()->name);
    }

    public function test_assignment_groups_update_repository()
    {
        $assignmentGroup = AssignmentGroup::factory()->create();
        $updatedData = $assignmentGroup->toArray();
        $updatedData['name'] = 'AssignmentGroup A Edited';

        /** @var BodyResponse $result */
        $result =  App::call(function (AssignmentGroupRepository $repository) use ($assignmentGroup, $updatedData) {
            return $repository->updateBy($updatedData, 'id', $assignmentGroup->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedData['name'], $result->getBodyData()->name);
    }

    public function test_assignment_groups_restore_repository()
    {
        $assignmentGroup = AssignmentGroup::factory()->create();
        $assignmentGroup->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (AssignmentGroupRepository $repository) use ($assignmentGroup) {
            return $repository->restoreBy('id', $assignmentGroup->id);
        });

        $dataFind = AssignmentGroup::where('id', $assignmentGroup->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($assignmentGroup->name, $dataFind->name);
    }

    public function test_assignment_groups_delete_repository()
    {
        $assignmentGroup = AssignmentGroup::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (AssignmentGroupRepository $repository) use ($assignmentGroup) {
            return $repository->deleteBy('id', $assignmentGroup->id);
        });

        $dataFind = AssignmentGroup::where('id', $assignmentGroup->id)->first();
        $dataDeleted = AssignmentGroup::onlyTrashed()->where('id', $assignmentGroup->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertSame($dataDeleted->name, $assignmentGroup->name);
    }

    public function test_assignment_groups_delete_permanently_repository()
    {
        $assignmentGroup = AssignmentGroup::factory()->create();
        $assignmentGroup->delete();

        /** @var BodyResponse $delete */
        $delete = App::call(function (AssignmentGroupRepository $repository) use ($assignmentGroup) {
            return $repository->permanentDeleteBy('id', $assignmentGroup->id);
        });

        $dataFind = AssignmentGroup::where('id', $assignmentGroup->id)->first();
        $dataDeleted = AssignmentGroup::onlyTrashed()->where('id', $assignmentGroup->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertNull($dataDeleted);
    }
}