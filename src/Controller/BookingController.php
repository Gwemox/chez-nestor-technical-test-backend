<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingCreateType;
use App\Service\CrudService\CrudServiceInterface;
use App\Service\CrudService\Exception\BadTypeException;
use App\Service\CrudService\Exception\FormNotValidException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookingController
 * @Route("/bookings", name="bookings_")
 * @package App\Controller
 */
class BookingController extends AbstractController
{
    /** @var CrudServiceInterface */
    private $crudService;
    private const ENTITY = Booking::class;

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
     * @Route("/{bookingId}",
     *     name="find_one",
     *     methods="GET",
     *     requirements={"bookingId"="^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"}
     * )
     * @param String $bookingId
     * @return JsonResponse
     * @throws BadTypeException
     */
    public function getOneById(string $bookingId)
    {
        return $this->json($this->crudService->getOneById(self::ENTITY, $bookingId));
    }

    /**
     * @Route("/", name="create", methods="POST")
     * @return JsonResponse
     * @throws FormNotValidException
     */
    public function post()
    {
        return $this->json(
            $this->crudService->post(self::ENTITY,BookingCreateType::class),
            201
        );
    }

    /**
     * @Route("/{bookingId}",
     *     name="delete",
     *     methods="DELETE",
     *     requirements={"bookingId"="^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"}
     * )
     * @param string $bookingId
     * @return JsonResponse
     * @throws BadTypeException
     * @throws FormNotValidException
     */
    public function delete(string $bookingId)
    {
        return $this->json(
            $this->crudService->delete(self::ENTITY, $bookingId)
        );
    }
}
