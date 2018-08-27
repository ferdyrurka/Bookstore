<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\AdminOrderController;
use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminOrderControllerTest
 * @package App\Tests\Controller\Admin
 */
class AdminOrderControllerTest extends WebTestCase
{
    private $guess;
    private $user;
    private $admin;

    public function setUp(): void
    {
        $client = new Client();
        $this->guess = $client->guess();
        $this->user = $client->signInUser();
        $this->admin = $client->signInAdmin();

        parent::setUp();
    }

    public function testPermission(): void
    {
        $this->guess->request('GET', '/admin1999/order/1');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/orders');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->guess->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/order/1');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/orders');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->user->getResponse()->getStatusCode());
    }

    public function testIndexAction(): void
    {
        $this->admin->request('GET', '/admin1999/order/1');
        $this->assertEquals(Response::HTTP_OK, $this->admin->getResponse()->getStatusCode());

        $this->admin->request('GET', '/admin1999/order/2');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->admin->getResponse()->getStatusCode());
    }

    public function testOrderListAction(): void
    {
        $this->admin->request('GET', '/admin1999/orders');
        $this->assertEquals(Response::HTTP_OK, $this->admin->getResponse()->getStatusCode());
    }
}
