<?php

namespace App\Tests\Service\CrudService\Validator;

use App\Service\CrudService\Exception\BadTypeException;
use App\Service\CrudService\Validator\IdValidatorFactory;
use App\Service\CrudService\Validator\IdValidatorInterface;
use PHPUnit\Framework\TestCase;

class IdValidatorFactoryTest extends TestCase
{
    public function testCanCreateValidatorWithGUID()
    {
        $result = IdValidatorFactory::create('guid');
        $this->assertInstanceOf(IdValidatorInterface::class, $result);
    }

    public function testCreateThrowErrorWithInvalidType()
    {
        $this->expectException(BadTypeException::class);
        $this->expectExceptionMessage('\'INVALID\' is not a known type of id');
        $result = IdValidatorFactory::create('INVALID');
    }
}
