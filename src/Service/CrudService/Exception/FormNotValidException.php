<?php

namespace App\Service\CrudService\Exception;

use App\Helper\ArrayHelper;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

class FormNotValidException extends BadRequestHttpException implements CrudServiceExceptionInterface
{
    public function __construct(FormInterface $form, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            ArrayHelper::recursiveImplode(
                ArrayHelper::dot(self::getErrorsFromForm($form)),
                ', ',
                true
            ),
            $previous,
            $code
        );
    }

    private static function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form as $fieldName => $formField) {
            foreach ($formField->getErrors() as $error) {
                if (!isset($errors[$fieldName])) {
                    $errors[$fieldName] = [];
                }
                $errors[$fieldName][] = $error->getMessage();
            }
        }

        /*foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = self::getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }*/
        return $errors;
    }
}