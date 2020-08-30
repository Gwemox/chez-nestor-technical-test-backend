<?php


namespace App\Service\CrudService\Validator;


interface IdValidatorInterface
{
    public function isValid($value): bool;
}