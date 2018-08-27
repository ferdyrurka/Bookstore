<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\AdminUserController;
use App\Entity\User;
use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AdminUserControllerTest
 * @package App\Tests\Controller\Admin
 */
class AdminUserControllerTest extends WebTestCase
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
        ##Guess

        $this->guess->request('GET', '/admin1999/delete-account/7');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/disable-account/7');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/activate-account/7');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/users');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        ##User

        $this->user->request('GET', '/admin1999/delete-account/7');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/disable-account/7');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/activate-account/7');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/users');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());
    }

    public function testIndex(): void
    {
        $this->admin->request('GET', '/admin1999/users');
        $this->assertEquals(200, $this->admin->getResponse()->getStatusCode());
    }

    public function testDisableAccount()
    {
        $this->admin->request('GET', '/admin1999/disable-account/7');
        $this->assertEquals(302, $this->admin->getResponse()->getStatusCode());

        $kernel = self::bootKernel();
        $user = $kernel->getContainer()->get('doctrine')->getManager()->getRepository(User::class)->find(7);
        $this->assertEquals(0, $user->getStatus());
    }

    public function testActivateAccount(): void
    {
        $this->admin->request('GET', '/admin1999/activate-account/7');
        $this->assertEquals(302, $this->admin->getResponse()->getStatusCode());

        $kernel = self::bootKernel();
        $user = $kernel->getContainer()->get('doctrine')->getManager()->getRepository(User::class)->find(7);
        $this->assertEquals(1, $user->getStatus());
    }


    public function testDeleteAccount(): void
    {
        $kernel = self::bootKernel();
        $em = $kernel->getContainer()->get('doctrine')->getManager();

        $user = new User();
        $user->setStatus(1);
        $user->setUsername('test_delete');
        $user->setEmail('delete@lukaszstaniszewski.pl');
        $user->setFirstName('Lorem ipusm');
        $user->setSurname('Lorem ipusm');
        $user->setRoles('ROLE_USER');
        $user->setPassword('test_oassword');

        $em->persist($user);
        $em->flush();

        $this->admin->request('GET', '/admin1999/delete-account/'.$user->getId());
        $this->assertEquals(302, $this->admin->getResponse()->getStatusCode());
    }
}
