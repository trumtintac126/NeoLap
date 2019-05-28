<?php
namespace App\Repositories\Validators\Row_name;
use Prettus\Validator\LaravelValidator;

/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 13:39
 */
class UpdateRownameValidator extends LaravelValidator
{
    public function getRules($action = null)
    {
        $this->rules = [
            'row_name' => 'required|string|max:255',
            'type' => 'required|string|max:255'
        ];
        return parent::getRules($action); 
    }

    public function getMessages()
    {
        $this->messages = [
            'row_name.required' => 'You must enter row name',
            'type.required' => 'You must enter type for row name'
        ];
        return parent::getMessages(); 
    }
}