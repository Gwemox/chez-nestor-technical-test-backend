<?php

namespace App\Service\CrudService;

use App\Service\CrudService\Exception\FormNotValidException;
use App\Service\CrudService\Validator\IdValidatorFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CrudService implements CrudServiceInterface
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var FormFactoryInterface */
    private $formFactory;
    /** @var RequestStack */
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager,
                                FormFactoryInterface $formFactory,
                                RequestStack $requestStack)
    {
        $this->em = $entityManager;
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
    }

    public function getAll(string $entity): array {
        return $this->getRepository($entity)
            ->findAll();
    }

    /**
     * @param string $entity
     * @param $id
     * @return object
     * @throws Exception\BadTypeException
     */
    public function getOneById(string $entity, $id) {
        return $this->getEntity($entity, $id);
    }

    /**
     * @param string $entity
     * @param string $formTypeClass
     * @return mixed
     * @throws FormNotValidException
     */
    public function post(string $entity, string $formTypeClass) {
        return $this->processForm($formTypeClass);
    }

    /**
     * @param string $entity
     * @param string $formTypeClass
     * @param $id
     * @return object|null
     * @throws Exception\BadTypeException
     * @throws FormNotValidException
     */
    public function edit(string $entity, string $formTypeClass, $id) {
        $data = $this->getEntity($entity, $id);
        return $this->processForm($formTypeClass, $data);
    }

    /**
     * @param string $entity
     * @param $id
     * @throws ORMException
     */
    public function delete(string $entity, $id) {
        $entity = $this->em->getReference($entity, $id);
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * @param string $formTypeClass
     * @param null $data
     * @return mixed
     * @throws FormNotValidException
     */
    private function processForm(string $formTypeClass, $data = null) {
        $request = $this->requestStack->getMasterRequest();
        $formData = array_merge($request->request->all(), $request->files->all());
        $form = $this->formFactory->create($formTypeClass, $data);
        $form->submit($formData, $request->getMethod() !== Request::METHOD_PATCH);
        if (!$form->isValid()) {
            throw new FormNotValidException($form);
        }

        $data = $form->getData();
        $this->em->persist($data);
        $this->em->flush();
        return $data;
    }

    /**
     * @param string $entity
     * @param $id
     * @return object|null
     * @throws Exception\BadTypeException|NotFoundHttpException
     */
    private function getEntity(string $entity, $id) {
        $metadata = $this->em->getClassMetadata($entity);
        $typeId = $metadata->getTypeOfField('id');
        $validator = IdValidatorFactory::create($typeId);

        if (!$validator->isValid($id)) {
            throw new BadRequestHttpException(sprintf('%s \'%s\' bad format !', ucfirst($metadata->getName()), $id));
        }

        $object = $this->getRepository($entity)
            ->find($id);
        if (null === $object) {
            throw new NotFoundHttpException(sprintf('%s \'%s\' not found !', ucfirst($metadata->getName()), $id));
        }

        return $object;
    }

    private function getRepository(string $entity): ObjectRepository {
        return $this->em->getRepository($entity);
    }
}