<?php

/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 24/05/2019
 * Time: 10:45
 */
namespace App\Validations;

use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;

class UserValidation extends LaravelValidator
{
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'email'         => 'required',
            'password'      => 'requied',
            'first_name'    => 'required',
            'last_name'     => 'required'
        ],
        ValidatorInterface::RULE_UPDATE => [
            'email'         => 'required',
            'password'      => 'requied',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'avatar_img'    => 'required'
        ]
    ];

    protected $messages = [
        'email.required'    => 'We need to know your e-mail address!',
        'password.requied'  => 'We need to know your password!'
    ];
}