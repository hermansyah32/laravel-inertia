<?php

namespace Tests\Feature\Repositories\Content;

use App\Http\Repositories\SubjectRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SubjectRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_subjects_index_repository()
    {
        $subject = Subject::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectRepository $repository) {
            return $repository->index();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_subjects_index_with_search_repository()
    {
        $subject = Subject::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectRepository $repository) use ($subject) {
            return $repository->index('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $subject->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_subjects_index_trashed_repository()
    {
        $subject = Subject::factory()->create();
        $subject->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectRepository $repository) {
            return $repository->indexTrashed();
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertCount(1, $result->getBodyData());
    }

    public function test_subjects_index_trashed_with_search_repository()
    {
        $subject = Subject::factory()->create();
        $subject->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectRepository $repository) use ($subject) {
            return $repository->indexTrashed('desc', ['col' => ['name'], 'comp' => ['eq'], 'val' => $subject->name]);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertArrayHasKey('current_page', $result->getBodyData()->toArray());
        $this->assertCount(1, $result->getBodyData()->toArray()['data']);
        $this->assertEquals(1, $result->getBodyData()->toArray()['total']);
    }

    public function test_subjects_create_repository()
    {
        $input = [
            'name' => 'Subject' . fake()->randomLetter(),
            'student_grade_id' => fake()->uuid(),
            'student_department_id' => fake()->uuid(),
        ];

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectRepository $repository) use ($input) {
            return $repository->create($input);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($input['name'], $result->getBodyData()->name);
    }

    public function test_subjects_get_repository()
    {
        $subject = Subject::factory()->create();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectRepository $repository) use ($subject) {
            return $repository->get('id', $subject->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subject->name, $result->getBodyData()->name);
    }

    public function test_subjects_get_trashed_repository()
    {
        $subject = Subject::factory()->create();
        $subject->delete();

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectRepository $repository) use ($subject) {
            return $repository->getTrashed('id', $subject->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subject->name, $result->getBodyData()->name);
    }

    public function test_subjects_update_repository()
    {
        $subject = Subject::factory()->create();
        $updatedData = $subject->toArray();
        $updatedData['name'] = 'Subject A Edited';

        /** @var BodyResponse $result */
        $result =  App::call(function (SubjectRepository $repository) use ($subject, $updatedData) {
            return $repository->updateBy($updatedData, 'id', $subject->id);
        });

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($updatedData['name'], $result->getBodyData()->name);
    }

    public function test_subjects_restore_repository()
    {
        $subject = Subject::factory()->create();
        $subject->delete();

        /** @var BodyResponse $result */
        $result = App::call(function (SubjectRepository $repository) use ($subject) {
            return $repository->restoreBy('id', $subject->id);
        });

        $dataFind = Subject::where('id', $subject->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $result);
        $this->assertSame($result->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertSame($subject->name, $dataFind->name);
    }

    public function test_subjects_delete_repository()
    {
        $subject = Subject::factory()->create();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubjectRepository $repository) use ($subject) {
            return $repository->deleteBy('id', $subject->id);
        });

        $dataFind = Subject::where('id', $subject->id)->first();
        $dataDeleted = Subject::onlyTrashed()->where('id', $subject->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertSame($dataDeleted->name, $subject->name);
    }

    public function test_subjects_delete_permanently_repository()
    {
        $subject = Subject::factory()->create();
        $subject->delete();

        /** @var BodyResponse $delete */
        $delete = App::call(function (SubjectRepository $repository) use ($subject) {
            return $repository->permanentDeleteBy('id', $subject->id);
        });

        $dataFind = Subject::where('id', $subject->id)->first();
        $dataDeleted = Subject::onlyTrashed()->where('id', $subject->id)->first();

        $this->assertInstanceOf(BodyResponse::class, $delete);
        $this->assertSame($delete->getResponseCode()->value, ResponseCode::OK->value);
        $this->assertNull($dataFind);
        $this->assertNull($dataDeleted);
    }
}
