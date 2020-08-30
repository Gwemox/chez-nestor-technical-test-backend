<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientCreateEditType;
use App\Service\CrudService\CrudServiceInterface;
use App\Service\CrudService\Exception\BadTypeException;
use App\Service\CrudService\Exception\FormNotValidException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ClientController
 * @Route("/clients", name="clients_")
 * @package App\Controller
 */
class ClientController extends AbstractController
{
    /** @var CrudServiceInterface */
    private $crudService;
    private const ENTITY = Client::class;

    public function __construct(CrudServiceInterface $crudService, SerializerInterface $serializer)
    {
        $this->crudService = $crudService;
        parent::__construct($serializer);
    }

    /**
     * @Route("/", name="list_all", methods="GET")
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return $this->json($this->crudService->getAll(self::ENTITY), 200, []);
    }

    /**
     * @Route("/{clientId}",
     *     name="find_one",
     *     methods="GET",
     *     requirements={"clientId"="^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"}
     * )
     * @param String $clientId
     * @return JsonResponse
     * @throws BadTypeException
     */
    public function getOneById(string $clientId)
    {
        return $this->json($this->crudService->getOneById(self::ENTITY, $clientId));
    }

    /**
     * @Route("/", name="create", methods="POST")
     * @return JsonResponse
     * @throws FormNotValidException
     */
    public function post()
    {
        return $this->json(
            $this->crudService->post(self::ENTITY,ClientCreateEditType::class),
            201
        );
    }

    /**
     * @Route("/{clientId}",
     *     name="edit",
     *     methods="PATCH",
     *     requirements={"clientId"="^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"}
     * )
     * @param string $clientId
     * @return JsonResponse
     * @throws BadTypeException
     * @throws FormNotValidException
     */
    public function edit(string $clientId)
    {
        return $this->json(
            $this->crudService->edit(self::ENTITY, ClientCreateEditType::class, $clientId),
            204
        );
    }

    /**
     * @Route("/{clientId}",
     *     name="delete",
     *     methods="DELETE",
     *     requirements={"clientId"="^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"}
     * )
     * @param string $clientId
     * @return JsonResponse
     * @throws BadTypeException
     * @throws FormNotValidException
     */
    public function delete(string $clientId)
    {
        return $this->json(
            $this->crudService->delete(self::ENTITY, $clientId)
        );
    }
}
