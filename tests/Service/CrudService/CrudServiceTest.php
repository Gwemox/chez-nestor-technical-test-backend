<?php

namespace App\Tests\Service\CrudService;

use App\Service\CrudService\CrudService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CrudServiceTest extends TestCase
{
    /**
     * @throws ORMException
     */
    public function testDeleteCallDoctrine()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $factory = $this->createMock(FormFactoryInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $crudService = new CrudService($em, $factory, $requestStack);

        $entityClass = 'Entity\TestEntity';
        $entityId = 'd622930f-ee5b-4d2e-8e48-50a1237ea3b3';
        $entity = new \stdClass();

        $em->expects($this->once())->method('getReference')->with($entityClass, $entityId)->willReturn($entity);
        $em->expects($this->once())->method('remove')->with($entity);
        $em->expects($this->once())->method('flush');

        $crudService->delete($entityClass, $entityId);
    }

    public function testGetOneByIdWithValidIdReturnEntity()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $factory = $this->createMock(FormFactoryInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $classMetadata = $this->createMock(ClassMetadata::class);
        $repository = $this->createMock(ObjectRepository::class);
        $crudService = new CrudService($em, $factory, $requestStack);

        $entityClass = 'Entity\TestEntity';
        $entityId = 'd622930f-ee5b-4d2e-8e48-50a1237ea3b3';
        $entity = new \stdClass();

        $em->method('getClassMetadata')->with($entityClass)->willReturn($classMetadata);
        $classMetadata->method('getTypeOfField')->with('id')->willReturn('guid');
        $em->method('getRepository')->with($entityClass)->willReturn($repository);
        $repository->expects($this->once())->method('find')->with($entityId)->willReturn($entity);

        $result = $crudService->getOneById($entityClass, $entityId);
        $this->assertEquals($entity, $result);
    }

    public function testGetOneByIdWithInvalidIdReturns400()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $factory = $this->createMock(FormFactoryInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $classMetadata = $this->createMock(ClassMetadata::class);
        $repository = $this->createMock(ObjectRepository::class);
        $crudService = new CrudService($em, $factory, $requestStack);

        $entityClass = 'Entity\TestEntity';
        $entityId = 'd62hgtezfza';

        $em->method('getClassMetadata')->with($entityClass)->willReturn($classMetadata);
        $classMetadata->method('getTypeOfField')->with('id')->willReturn('guid');
        $em->method('getRepository')->with($entityClass)->willReturn($repository);
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("'$entityId' bad format !");
        $crudService->getOneById($entityClass, $entityId);
    }

    public function testPost()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $factory = Forms::createFormFactoryBuilder()->getFormFactory();
        $requestStack = $this->createMock(RequestStack::class);
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag->method('all')->willReturn(['toto']);
        $request = $this->createMock(Request::class);
        $request->request = $parameterBag;
        $request->files = $parameterBag;
        $crudService = new CrudService($em, $factory, $requestStack);

        $requestStack->expects($this->once())->method('getMasterRequest')->willReturn($request);
        $entityClass = 'Entity\TestEntity';

        $em->expects($this->once())->method('persist')->withAnyParameters();
        $em->expects($this->once())->method('flush');

        $result = $crudService->post($entityClass, FormType::class);
        $this->assertEquals([], $result);
    }

    public function testGetAll()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $factory = $this->createMock(FormFactoryInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $repository = $this->createMock(ObjectRepository::class);
        $crudService = new CrudService($em, $factory, $requestStack);

        $entityClass = 'Entity\TestEntity';

        $em->method('getRepository')->with($entityClass)->willReturn($repository);
        $assertResult = ['Toto', new \stdClass()];
        $repository->expects($this->once())->method('findAll')->willReturn($assertResult);
        $result = $crudService->getAll($entityClass);

        $this->assertEquals($assertResult, $result);
    }

    public function testEdit()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $factory = Forms::createFormFactory();
        $requestStack = $this->createMock(RequestStack::class);
        $classMetadata = $this->createMock(ClassMetadata::class);
        $repository = $this->createMock(ObjectRepository::class);
        $crudService = new CrudService($em, $factory, $requestStack);
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $request = $this->createMock(Request::class);
        $assertResult = ['toto' => 'La tête à toto'];
        $parameterBag->method('all')->willReturn($assertResult);
        $request->request = $parameterBag;
        $request->files = $parameterBag;
        $requestStack->expects($this->once())->method('getMasterRequest')->willReturn($request);

        $entityClass = 'Entity\TestEntity';
        $entityId = 'd622930f-ee5b-4d2e-8e48-50a1237ea3b3';
        $entity = ['toto' => 'test 1'];



        $em->method('getClassMetadata')->with($entityClass)->willReturn($classMetadata);
        $classMetadata->method('getTypeOfField')->with('id')->willReturn('guid');
        $em->method('getRepository')->with($entityClass)->willReturn($repository);
        $repository->expects($this->once())->method('find')->with($entityId)->willReturn($entity);

        $result = $crudService->edit($entityClass, TestFormType::class, $entityId);
        $this->assertEquals($assertResult, $result);

    }
}

class TestFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('toto')
        ;
    }
}