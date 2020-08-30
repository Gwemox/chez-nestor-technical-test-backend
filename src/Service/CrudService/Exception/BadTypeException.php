<?php

namespace App\Service\CrudService\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BadTypeException extends BadRequestHttpException implements CrudServiceExceptionInterface
{
}