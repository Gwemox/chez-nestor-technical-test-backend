<?php


namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        if ($this->serializer instanceof SerializerInterface) {
            $json = $this->serializer->serialize($data, 'json',
                SerializationContext::create()
                    ->setSerializeNull(true)
            );
            return new JsonResponse($json, $status, $headers, true);
        } else {
            return parent::json($data, $status, $headers, $context);
        }
    }

}