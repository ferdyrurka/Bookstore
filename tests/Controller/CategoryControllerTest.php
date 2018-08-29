<?php

namespace App\Tests\Controller;

use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CategoriesControllerTest
 * @package App\Tests\Controller
 */
class CategoryControllerTest extends WebTestCase
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
        $this->guess->request('GET', '/categories');
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $this->user->request('GET', '/categories');
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());
    }
}
