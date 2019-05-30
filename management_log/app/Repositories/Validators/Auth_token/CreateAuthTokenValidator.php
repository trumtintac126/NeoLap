<?php

namespace App\Repositories\Validators\Auth_token;

use Prettus\Validator\LaravelValidator;

class CreateAuthTokenValidator extends LaravelValidator
{
    public function getRules($action = null)
    {
        return parent::getRules($action);
    }
}