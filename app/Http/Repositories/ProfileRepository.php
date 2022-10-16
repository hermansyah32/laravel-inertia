<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseAccountRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Lang;

class ProfileRepository extends BaseAccountRepository
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
        parent::__construct($app, Lang::get('data.user'));
    }
}
