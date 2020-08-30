<?php

namespace App\Tests\Controller;

use App\Controller\ApartmentController;
use App\Entity\Apartment;
use App\Service\CrudService\CrudServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApartmentControllerTest extends WebTestCase
{
    public function testPostJSON()
    {
        $client = static::createClient();
        $server = array('CONTENT_TYPE' => 'application/json', 'ACCEPT' => 'application/json');
        $data = [
            'city' => 'Lyon',
            'name' => 'Chez Thibault',
            'street' => '12 rue nestor',
            'zipCode' => '69007'
        ];

        $client->request('POST', '/apartments/', [], [], $server, json_encode($data));
        $response = $client->getResponse();

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $result = json_decode($response->getContent(), true);
        $this->assertEquals($data['city'], $result['city']);
        $this->assertEquals($data['name'], $result['name']);
        $this->assertEquals($data['street'], $result['street']);
        $this->assertEquals($data['zipCode'], $result['zipCode']);
    }

    public function testGetAll()
    {
        $client = static::createClient();

        $client->request('GET', '/apartments/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        /*$this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crudService = $this->createMock(CrudServiceInterface::class);
        $crudService->expects($this->once())->method('getAll')->with($this->equalTo(Apartment::class));
        $controller = new ApartmentController($crudService);
        $controller->getAll();*/
    }

   /* public function testEdit()
    {

    }

    public function testPost()
    {

    }

    public function testGetOneById()
    {

    } */
}
