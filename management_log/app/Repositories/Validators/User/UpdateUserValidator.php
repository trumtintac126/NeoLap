<?php

namespace App\Repositories\Validators\User;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class UpdateUserValidator.
 *
 * @package namespace App\Repositories\Validators\User;
 */
class UpdateUserValidator extends LaravelValidator
{
    public function getRules($action = null)
    {
        $this->rules = [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
        ];

        return parent::getRules($action);
    }
}
