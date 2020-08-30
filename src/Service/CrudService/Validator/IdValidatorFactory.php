<?php

namespace App\Service\CrudService\Validator;

use App\Service\CrudService\Exception\BadTypeException;

class IdValidatorFactory
{
    /**
     * @param string $type
     * @return IdValidatorInterface
     * @throws BadTypeException
     */
    public static function create(string $type): IdValidatorInterface
    {
        switch ($type) {
            case 'guid':
                return new GUIDValidator();
            default:
                throw new BadTypeException(sprintf('\'%s\' is not a known type of id', $type));
        }
    }
}