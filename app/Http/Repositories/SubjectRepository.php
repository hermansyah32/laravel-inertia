<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseRepository;
use App\Models\Subject;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Lang;

class SubjectRepository extends BaseRepository
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
        parent::__construct($app, Lang::get('data.student_grades'));
    }

    /**
     * Set model class
     * @return Model
     */
    public function model()
    {
        return $this->model = Subject::class;
    }

    /**
     * Get create validation
     * @return object Object of rules, messages, attributes. 
     */
    public function createValidation()
    {
        return ((object) [
            'rules' => [
                'name' => ['required', 'string', 'unique:student_grades'],
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
