<?php

namespace App\Controller;

use App\Entity\Apartment;
use App\Form\ApartmentCreateType;
use App\Service\CrudService\CrudService;
use App\Service\CrudService\CrudServiceInterface;
use App\Service\CrudService\Exception\BadTypeException;
use App\Service\CrudService\Exception\FormNotValidException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApartmentController
 * @Route("/apartments", name="apartments_")
 * @package App\Controller
 */
class ApartmentController extends AbstractController
{
    /** @var CrudServiceInterface */
    private $crudService;
    private const ENTITY = Apartment::class;

    public function __construct(CrudServiceInterface $crudService)
    {
        $this->crudService = $crudService;
    }

    /**
     * @Route("/", name="list_all", methods="GET")
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return $this->json($this->crudService->getAll(self::ENTITY));
    }

    /**
     * @Route("/{apartmentId}", name="find_one", methods="GET")
     * @param String $apartmentId
     * @return JsonResponse
     * @throws BadTypeException
     */
    public function getOneById(string $apartmentId)
    {
        return $this->json($this->crudService->getOneById(self::ENTITY, $apartmentId));
    }

    /**
     * @Route("/", name="create", methods="POST")
     * @return JsonResponse
     * @throws FormNotValidException
     */
    public function post()
    {
        return $this->json(
            $this->crudService->post(self::ENTITY,ApartmentCreateType::class),
            201
        );
    }

    /**
     * @Route("/{apartmentId}", name="edit", methods="PATCH")
     * @param string $apartmentId
     * @return JsonResponse
     * @throws BadTypeException
     * @throws FormNotValidException
     */
    public function edit(string $apartmentId)
    {
        return $this->json(
            $this->crudService->edit(self::ENTITY, ApartmentCreateType::class, $apartmentId)
        );
    }
}
