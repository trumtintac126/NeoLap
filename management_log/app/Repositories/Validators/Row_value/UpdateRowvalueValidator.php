<?php
namespace App\Repositories\Validators\Row_value;

use Prettus\Validator\LaravelValidator;

class UpdateRowvalueValidator extends LaravelValidator
{
    public function getRules($action = null)
    {
        return parent::getRules($action); 
    }
}