<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseRepository;
use App\Models\SubjectReference;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Lang;

class SubjectReferenceRepository extends BaseRepository
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
        parent::__construct($app, Lang::get('data.subject_references'));
    }

    /**
     * Set model class
     * @return Model
     */
    public function model()
    {
        return $this->model = SubjectReference::class;
    }

    /**
     * Get create validation
     * @return object Object of rules, messages, attributes. 
     */
    public function createValidation()
    {
        return ((object) [
            'rules' => [
                'subject_content_id' => ['required', 'uuid'],
                'name' => ['required', 'string'],
                'url' => ['required', 'string'],
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
                'subject_content_id' => ['required', 'uuid'],
                'name' => ['required', 'string'],
                'url' => ['required', 'string'],
            ],
            'messages' => [],
            'attributes' => []
        ]);
    }
}
