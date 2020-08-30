<?php

namespace App\Tests\Controller;

use App\Controller\ApartmentController;
use App\Entity\Apartment;
use App\Service\CrudService\CrudServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        return $result['id'];
    }

    /**
     * @depends testPostJSON
     */
    public function testGetAll()
    {
        $client = static::createClient();

        $client->request('GET', '/apartments/');
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
        $client->request('GET', sprintf('/apartments/%s', $id));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals([
            'id' => $id,
            'city' => 'Lyon',
            'name' => 'Chez Thibault',
            'street' => '12 rue nestor',
            'zipCode' => '69007'
        ], $responseData);
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
            'name' => 'Chez Mamie',
            'street' => '12 rue du castor',
        ];

        $client->request('PATCH', sprintf('/apartments/%s', $id), [], [], $server, json_encode($data));
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

        $client->request('DELETE', sprintf('/apartments/%s', $id));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}
