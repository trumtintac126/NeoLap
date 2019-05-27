<?php

namespace App\Services\Concerns;

trait HasAttributeHooks
{
    protected function beforeCreate(array $attributes)
    {
        return $attributes;
    }

    protected function beforeUpdate(array $attributes)
    {
        return $attributes;
    }
}