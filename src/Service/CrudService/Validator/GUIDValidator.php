<?php

namespace App\Service\CrudService\Validator;

class GUIDValidator implements IdValidatorInterface
{
    public function isValid($value): bool
    {
       return is_string($value) && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $value) === 1;
    }
}