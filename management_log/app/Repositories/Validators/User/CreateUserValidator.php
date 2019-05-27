<?php

namespace App\Repositories\Validators\User;

use Illuminate\Validation\Rule;
use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class CreateUserValidator.
 *
 * @package namespace App\Repositories\Validators\User;
 */
class CreateUserValidator extends LaravelValidator
{
    /**
     * Get rule for validation by action ValidatorInterface::RULE_CREATE or ValidatorInterface::RULE_UPDATE
     *
     * Default rule: ValidatorInterface::RULE_CREATE
     *
     * @param null $action
     * @return array
     */
    public function getRules($action = null)
    {
        $this->rules = [
            'email' => 'required|string|max:255|email|unique:customer_users',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'password' => 'required|max:255|min:6',
        ];

        return parent::getRules($action);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        $this->messages = [
            'email.required' => 'We need to know your e-mail address!',
        ];

        return parent::getMessages();
    }

    public function getAttributes()
    {
        $this->attributes = [
            'email' => 'E-mail',
        ];

        return parent::getAttributes();
    }
}
