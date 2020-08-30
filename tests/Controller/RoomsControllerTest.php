<?php

namespace App\Tests\Controller;

use App\Controller\ApartmentController;
use App\Entity\Apartment;
use App\Service\CrudService\CrudServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoomsControllerTest extends WebTestCase
{
    public function testPostJSON()
    {
        $client = static::createClient();
        $server = array('CONTENT_TYPE' => 'application/json', 'ACCEPT' => 'application/json');
        $data = [
            'number' => 158,
            'area' => 1465,
            'price' => 51255,
            'apartment' => [
                "name" => "Chez thib",
                "street" => "12 rue Sabine Zlatin",
                "zipCode" => "69007",
                "city"=> "Lyon"
            ]
        ];

        $client->request('POST', '/rooms/', [], [], $server, json_encode($data));
        $response = $client->getResponse();

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $result = json_decode($response->getContent(), true);
        $this->assertEquals($data['number'], $result['number']);
        $this->assertEquals($data['area'], $result['area']);
        $this->assertEquals($data['price'], $result['price']);

        return $result['id'];
    }

    /**
     * @depends testPostJSON
     */
    public function testGetAll()
    {
        $client = static::createClient();

        $client->request('GET', '/rooms/');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(1, $responseData);
    }

    /**
     * @depends testPostJSON
     * @param string $id
     */
    public function testGetOneById(string $id)
    {
        $client = static::createClient();
        $this->assertNotNull($id);
        $client->request('GET', sprintf('/rooms/%s', $id));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $responseData);
    }

    /**
     * @depends testPostJSON
     * @param string $id
     */
    public function testEdit(string $id)
    {
        $client = static::createClient();
        $this->assertNotNull($id);
        $server = array('CONTENT_TYPE' => 'application/json', 'ACCEPT' => 'application/json');
        $data = [
            'price' => 6666,
        ];

        $client->request('PATCH', sprintf('/rooms/%s', $id), [], [], $server, json_encode($data));
        $response = $client->getResponse();

        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * @depends testPostJSON
     * @param string $id
     */
    public function testDelete(string $id)
    {
        $client = static::createClient();
        $this->assertNotNull($id);

        $client->request('DELETE', sprintf('/rooms/%s', $id));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}
