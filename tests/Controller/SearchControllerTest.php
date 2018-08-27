<?php

namespace App\Tests\Controller;

use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SearchControllerTest
 * @package App\Tests\Controller
 */
class SearchControllerTest extends WebTestCase
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

    public function testSearch(): void
    {
        $crawler = $this->guess->request('GET', '/search?q=tests+second');
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('.product'));

        $crawler = $this->user->request('GET', '/search?q=tests+second');
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('.product'));
    }
}
