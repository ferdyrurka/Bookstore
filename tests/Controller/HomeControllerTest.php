<?php

namespace App\Tests\Controller;

use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class HomeControllerTest
 * @package App\Tests\Controller
 */
class HomeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = new Client();
        $guess = $client->guess();
        $user = $client->signInUser();

        $guess->request('GET', '/');
        $this->assertEquals(200, $guess->getResponse()->getStatusCode());

        $user->request('GET', '/');
        $this->assertEquals(200, $user->getResponse()->getStatusCode());
    }
}
