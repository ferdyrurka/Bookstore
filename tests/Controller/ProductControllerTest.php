<?php

namespace App\Tests\Controller;

use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ProductControllerTest
 * @package App\Tests\Controller
 */
class ProductControllerTest extends WebTestCase
{
    private $guess;
    private $user;

    public function setUp(): void
    {
        $client = new Client();
        $this->guess = $client->guess();
        $this->user = $client->signInUser();

        parent::setUp();
    }

    public function testIndexAction(): void
    {
        $this->guess->request('GET', '/product/tests');
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $this->user->request('GET', '/product/tests');
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());
    }

    public function testProductListAction(): void
    {
        $this->guess->request('GET', '/products');
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $this->user->request('GET', '/products');
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());
    }
}
