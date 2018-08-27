<?php

namespace App\Tests\Controller;

use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CartControllerTest
 * @package App\Tests\Controller
 */
class CartControllerTest extends WebTestCase
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

    public function testPermission(): void
    {
        $this->user->request('GET', '/cart/add/1/1');
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/cart/update/1/3/' . $this->user->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/cart/delete/1/' . $this->user->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/cart/delete-cart/' . $this->user->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());
    }

    public function testCartControllerGuess(): void
    {

        /**
         * AddProductAction
         */

        $this->guess->request('GET', '/cart/add/1/1');
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/cart/add/2/2/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/cart/add/1/-2/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(500, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/cart/add/3/2/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(404, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/cart/add/1/999/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        /**
         * Update action
         */

        $this->guess->request('GET', '/cart/update/1/3/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/cart/update/1/1/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/cart/update/1/1');
        $this->assertEquals(404, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/cart/update/1/-2/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(500, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/cart/update/3/2/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(500, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/cart/update/1/999/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Too many pieces of the product."}', $this->guess->getResponse()->getContent());

        /**
         * Delete action
         */

        $this->guess->request('GET', '/cart/delete/999/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(500, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/cart/delete/1/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        /**
         * Delete cart
         */

        $this->guess->request('GET', '/cart/delete-cart/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/cart/delete-cart/' . $this->guess->getRequest()->getSession()->get('cart_id'));
        $this->assertEquals(404, $this->guess->getResponse()->getStatusCode());
    }
}
