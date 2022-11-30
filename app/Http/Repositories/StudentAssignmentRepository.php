<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseRepository;
use App\Models\StudentAssignment;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Lang;

class StudentAssignmentRepository extends BaseRepository
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
        parent::__construct($app, Lang::get('data.student_assignments'));
    }

    /**
     * Set model class
     * @return Model
     */
    public function model()
    {
        return $this->model = StudentAssignment::class;
    }

    /**
     * Get create validation
     * @return object Object of rules, messages, attributes. 
     */
    public function createValidation()
    {
        return ((object) [
            'rules' => [
                'user_id' => ['required', 'uuid'],
                'assignment_group_id' => ['required', 'uuid'],
                'subject_assignment_id' => ['required', 'uuid'],
                'answer' => ['required', 'string'],
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
                'user_id' => ['required', 'uuid'],
                'assignment_group_id' => ['required', 'uuid'],
                'subject_assignment_id' => ['required', 'uuid'],
                'answer' => ['required', 'string'],
            ],
            'messages' => [],
            'attributes' => []
        ]);
    }
}
