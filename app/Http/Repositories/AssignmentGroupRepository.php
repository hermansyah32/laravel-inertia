<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseRepository;
use App\Models\AssignmentGroup;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Lang;

class AssignmentGroupRepository extends BaseRepository
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
        return $this->model = AssignmentGroup::class;
    }

    /**
     * Get create validation
     * @return object Object of rules, messages, attributes. 
     */
    public function createValidation()
    {
        return ((object) [
            'rules' => [
                'subject_group_id' => ['required', 'integer'],
                'subject_content_id' => ['required', 'integer'],
                'title' => ['required', 'string'],
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
                'subject_group_id' => ['required', 'integer'],
                'subject_content_id' => ['required', 'integer'],
                'title' => ['required', 'string'],
            ],
            'messages' => [],
            'attributes' => []
        ]);
    }
}
