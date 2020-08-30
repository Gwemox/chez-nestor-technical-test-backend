<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomCreateEditType;
use App\Repository\RoomRepository;
use App\Service\CrudService\CrudServiceInterface;
use App\Service\CrudService\Exception\BadTypeException;
use App\Service\CrudService\Exception\FormNotValidException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RoomController
 * @Route("/rooms", name="rooms_")
 * @package App\Controller
 */
class RoomController extends AbstractController
{
    /** @var CrudServiceInterface */
    private $crudService;
    private const ENTITY = Room::class;

    /** @var RoomRepository */
    private $roomRepository;

    public function __construct(CrudServiceInterface $crudService,
                                RoomRepository $roomRepository,
                                SerializerInterface $serializer)
    {
        $this->crudService = $crudService;
        $this->roomRepository = $roomRepository;
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
     * @Route("/apartment/{apartmentId}", name="list_all_by_apart", methods="GET")
     * @param string $apartmentId
     * @return JsonResponse
     */
    public function getAllByApartment(string $apartmentId): JsonResponse
    {
        return $this->json($this->roomRepository->findByApartment($apartmentId), 200, []);
    }

    /**
     * @Route("/{roomId}",
     *     name="find_one",
     *     methods="GET",
     *     requirements={"roomId"="^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"}
     * )
     * @param String $roomId
     * @return JsonResponse
     * @throws BadTypeException
     */
    public function getOneById(string $roomId)
    {
        return $this->json($this->crudService->getOneById(self::ENTITY, $roomId));
    }

    /**
     * @Route("/", name="create", methods="POST")
     * @return JsonResponse
     * @throws FormNotValidException
     */
    public function post()
    {
        return $this->json(
            $this->crudService->post(self::ENTITY,RoomCreateEditType::class),
            201
        );
    }

    /**
     * @Route("/{roomId}",
     *     name="edit",
     *     methods="PATCH",
     *     requirements={"roomId"="^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"}
     * )
     * @param string $roomId
     * @return JsonResponse
     * @throws BadTypeException
     * @throws FormNotValidException
     */
    public function edit(string $roomId)
    {
        return $this->json(
            $this->crudService->edit(self::ENTITY, RoomCreateEditType::class, $roomId),
            204
        );
    }

    /**
     * @Route("/{roomId}",
     *     name="delete",
     *     methods="DELETE",
     *     requirements={"roomId"="^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"}
     * )
     * @param string $roomId
     * @return JsonResponse
     * @throws BadTypeException
     * @throws FormNotValidException
     */
    public function delete(string $roomId)
    {
        return $this->json(
            $this->crudService->delete(self::ENTITY, $roomId)
        );
    }
}
