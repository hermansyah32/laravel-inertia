<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseRepository;
use App\Models\SubjectGroup;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Lang;

class SubjectGroupRepository extends BaseRepository
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
        return $this->model = SubjectGroup::class;
    }

    /**
     * Get create validation
     * @return object Object of rules, messages, attributes. 
     */
    public function createValidation()
    {
        return ((object) [
            'rules' => [
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
                'name' => ['required', 'string'],
            ],
            'messages' => [],
            'attributes' => []
        ]);
    }
}