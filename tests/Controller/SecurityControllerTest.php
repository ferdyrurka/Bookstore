<?php

namespace App\Tests\Controller;

use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest
 * @package App\Tests\Controller
 */
class SecurityControllerTest extends WebTestCase
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
        $this->user->request('GET', '/sign-in');
        $this->assertEquals(403, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/sign-up');
        $this->assertEquals(403, $this->user->getResponse()->getStatusCode());

        $this->guess->request('GET', '/log-out');
        $this->assertEquals(302, $this->guess->getResponse()->getStatusCode());
    }

    public function testSignIn(): void
    {
        $this->guess->request('GET', '/sign-in');
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());
    }

    public function testSignUp(): void
    {
        $this->guess->request('GET', '/sign-up');
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());
    }

    public function testLogOut(): void
    {
        $this->user->request('GET', '/log-out');
        $this->assertEquals(302, $this->user->getResponse()->getStatusCode());
    }
}
