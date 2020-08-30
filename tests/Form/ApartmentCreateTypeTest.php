<?php

namespace App\Tests\Form;

use App\Entity\Apartment;
use App\Form\ApartmentCreateEditType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class ApartmentCreateTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        // or if you also need to read constraints from annotations
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidDataReturnObjectWithoutError()
    {
        $formData = [
            'city' => 'Lyon',
            'name' => 'Chez Thibault',
            'street' => '12 rue de nestor',
            'zipCode' => '69001'
        ];

        $model = new Apartment();
        $form = $this->factory->create(ApartmentCreateEditType::class, $model);

        $expected = new Apartment();
        $expected->setCity('Lyon');
        $expected->setName('Chez Thibault');
        $expected->setStreet('12 rue de nestor');
        $expected->setZipCode('69001');

        $form->submit($formData);

        //$this->assertTrue($form->isValid());

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $formData was modified as expected when the form was submitted
        $this->assertEquals($expected, $model);
    }

    public function testSubmitInvalidDataReturnErrors()
    {
        $formData = [
            'city' => 'Lyon',
            'name' => 'Chez Thibault',
            'street' => null,
            'zipCode' => '6900'
        ];

        $model = new Apartment();
        $form = $this->factory->create(ApartmentCreateEditType::class, $model);

        $expected = new Apartment();
        $expected->setCity('Lyon');
        $expected->setName('Chez Thibault');
        $expected->setZipCode('6900');

        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->getErrors(true);
        $this->assertGreaterThanOrEqual(1, count($errors));

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $formData was modified as expected when the form was submitted
        $this->assertEquals($expected, $model);
    }
}
