<?php
namespace App\Repositories\Validators\Row_value;
use Prettus\Validator\LaravelValidator;

class CreateRowvalueValidator extends LaravelValidator
{
    public function getRules($action = null)
    {
        return parent::getRules($action); 
    }
    
    public function getMessages()
    {
        return parent::getMessages(); 
    }
}