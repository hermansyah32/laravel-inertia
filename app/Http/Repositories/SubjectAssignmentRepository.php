<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseRepository;
use App\Models\SubjectAssignment;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Lang;

class SubjectAssignmentRepository extends BaseRepository
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
        return $this->model = SubjectAssignment::class;
    }

    /**
     * Get create validation
     * @return object Object of rules, messages, attributes. 
     */
    public function createValidation()
    {
        return ((object) [
            'rules' => [
                'assignment_group_id' => ['required', 'integer'],
                'type' => ['required', 'string'],
                'question' => ['required', 'string'],
                'options' => ['required', 'string'],
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
                'assignment_group_id' => ['required', 'integer'],
                'type' => ['required', 'string'],
                'question' => ['required', 'string'],
                'options' => ['required', 'string'],
                'answer' => ['required', 'string'],
            ],
            'messages' => [],
            'attributes' => []
        ]);
    }
}