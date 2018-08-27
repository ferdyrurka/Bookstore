<?php

namespace App\Tests\Event;

use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SessionAttackSubscriberTest
 * @package App\Tests\Event
 */
class SessionAttackSubscriberTest extends WebTestCase
{
    public function testSessionAttack(): void
    {
        $user = new Client();
        $user = $user->signInUser();

        $user->request('GET', '/');
        $this->assertEquals(Response::HTTP_OK, $user->getResponse()->getStatusCode());

        $user->setServerParameters(array(
            'HTTP_HOST' => '127.0.0.3',
            'HTTP_USER_AGENT' => 'Windows 10',
        ));

        $user->request('GET', '/');
        $this->assertEquals(Response::HTTP_FOUND, $user->getResponse()->getStatusCode());
    }
}
