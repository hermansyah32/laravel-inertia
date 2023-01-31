<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseRepository;
use App\Http\Response\BodyResponse;
use App\Models\StudentClass;
use App\Models\StudentProfile;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class StudentClassRepository extends BaseRepository
{
    /**
     * Base repository Constructor
     *
     * @param Application $app
     * @param string $messageResponseKey
     * @throws Exception
     */
    public function __construct(Application $app)
    {
        parent::__construct($app, Lang::get('data.student_classes'));
    }

    /**
     * Set model class
     * @return Model
     */
    public function model()
    {
        return $this->model = StudentClass::class;
    }

    /**
     * Get create validation
     * @return object Object of rules, messages, attributes. 
     */
    public function createValidation()
    {
        return ((object) [
            'rules' => [
                'author_id' => ['nullable', 'uuid'],
                'student_grade_id' => ['required', 'uuid'],
                'student_department_id' => ['required', 'uuid'],
                'name' => ['required', 'string'],
            ],
            'messages' => [],
            'attributes' => []
        ]);
    }

    /**
     * Get update validation
     * @return object Object of rules, messages, attributes. 
     */
    public function updateValidation()
    {
        return ((object) [
            'rules' => [
                'author_id' => ['nullable', 'uuid'],
                'student_grade_id' => ['required', 'uuid'],
                'student_department_id' => ['required', 'uuid'],
                'name' => ['required', 'string'],
            ],
            'messages' => [],
            'attributes' => []
        ]);
    }

    /**
     * Student assign validation
     * @return object Object of rules, messages, attributes. 
     */
    public function studentAssignValidation()
    {
        return ((object) [
            'rules' => [
                'studentId' => ['required', 'array'],
                'studentId.*' => ['required', 'uuid', 'distinct']
            ],
            'messages' => [],
            'attributes' => []
        ]);
    }

    /**
     * Update a record by where condition
     *
     * @param array $input Student id array
     * @return BodyResponse
     */
    public function assignStudents(array $input, string $classId): BodyResponse
    {
        $body = new BodyResponse();
        try {
            $validator = Validator::make(
                $input,
                $this->studentAssignValidation()->rules,
                $this->studentAssignValidation()->messages,
                $this->studentAssignValidation()->attributes
            );
            if ($validator->fails())
                return $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);

            StudentProfile::whereIn('user_id', $input['studentId'])->update(['student_class_id' => $classId]);

            $body->setBodyMessage($this->messageResponse['successUpdated']);
        } catch (\Throwable $th) {
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
        }
        return $body;
    }
}
