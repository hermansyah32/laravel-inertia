<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseRepository;
use App\Models\SubjectContent;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Lang;

class SubjectContentRepository extends BaseRepository
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
        parent::__construct($app, Lang::get('data.subject_contents'));
    }

    /**
     * Set model class
     * @return Model
     */
    public function model()
    {
        return $this->model = SubjectContent::class;
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
                'subject_group_id' => ['required', 'uuid'],
                'order' => ['required', 'integer'],
                'type' => ['required', 'string'],
                'thumbnail' => ['required', 'string'],
                'video_url' => ['required', 'string'],
                'title' => ['required', 'string'],
                'content' => ['required', 'string'],
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
                'subject_group_id' => ['required', 'uuid'],
                'order' => ['required', 'integer'],
                'type' => ['required', 'string'],
                'thumbnail' => ['required', 'string'],
                'video_url' => ['nullable', 'string'],
                'title' => ['required', 'string'],
                'content' => ['required', 'string'],
            ],
            'messages' => [],
            'attributes' => []
        ]);
    }
}
