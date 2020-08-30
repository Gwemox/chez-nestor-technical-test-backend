<?php

namespace App\Tests\Service\CrudService\Validator;

use App\Service\CrudService\Validator\GUIDValidator;
use PHPUnit\Framework\TestCase;

class GUIDValidatorTest extends TestCase
{

    public function testIsValidWithValidGUIDReturnsTrue()
    {
        $validator = new GUIDValidator();
        $result = $validator->isValid('d622930f-ee5b-4d2e-8e48-50a1237ea3b3');
        $this->assertEquals(true, $result);
    }

    public function testIsValidWithInvalidGUIDReturnsFalse()
    {
        $validator = new GUIDValidator();
        $result = $validator->isValid('INVALID');
        $this->assertEquals(false, $result);
    }

    public function testIsValidWithNullReturnsFalse()
    {
        $validator = new GUIDValidator();
        $result = $validator->isValid(null);
        $this->assertEquals(false, $result);
    }
}
