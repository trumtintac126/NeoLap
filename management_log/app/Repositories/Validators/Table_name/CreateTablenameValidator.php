<?php
namespace App\Repositories\Validators\Table_name;

use \Prettus\Validator\LaravelValidator;
use Illuminate\Validation\Rule;

/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 08:52
 */
class CreateTablenameValidator extends LaravelValidator
{
    public function getRules($action = null)
    {
        $this->rules = [
            'table_name' => 'required|string|max:255'
        ];
        return parent::getRules($action);
    }

    public function getMessages()
    {
        $this->messages = [
          'table_name.required' => 'You must enter table name!'
        ];
        return parent::getMessages();
    }
}