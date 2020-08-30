<?php

namespace App\Tests\Service\CrudService\Exception;

use App\Service\CrudService\Exception\FormNotValidException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormConfigBuilder;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class FormNotValidExceptionTest extends TypeTestCase
{

    public function testConstructWithErrorsFormReturnValidMessage() {
        $form = $this->factory->createBuilder()
            ->add('toto')
            ->getForm()
        ;
        $form->get('toto')->addError(new FormError('Test child message'));
        $form->addError(new FormError('Test message'));
        $form->addError(new FormError('Test message2'));

        $exception = new FormNotValidException($form);
        $this->assertEquals('0 : Test message, 1 : Test message2, toto.0 : Test child message', $exception->getMessage());
    }
}
