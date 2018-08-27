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

    public function testProductCategoryListAction(): void
    {
        $this->guess->request('GET', '/products-in-category/tests-category');
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/products-in-category/not-found');
        $this->assertEquals(404, $this->guess->getResponse()->getStatusCode());

        $crawler = $this->user->request('GET', '/products-in-category/tests-category');
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('.product'));
    }
}
