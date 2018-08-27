<?php

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AdminSecurityControllerTest
 * @package App\Tests\Controller\Admin
 */
class AdminSecurityControllerTest extends WebTestCase
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

        $this->guess->request('GET', '/admin1999/create-admin');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999');
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/log-out');
        $this->assertEquals(302, $this->guess->getResponse()->getStatusCode());

        ##User

        $this->user->request('GET', '/admin1999/create-admin');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999');
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/log-out');
        $this->assertEquals(302, $this->user->getResponse()->getStatusCode());
    }

    public function testCreateAdminAction(): void
    {
        $crawler = $this->admin->request('GET', '/admin1999/create-admin');
        $this->assertEquals(200, $this->admin->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('create_admin_form[save]');

        $formData = array(
            'create_admin_form[username]' => 'New_admin',
            'create_admin_form[plain_password][first]' => 'admin123456789',
            'create_admin_form[plain_password][second]' => 'admin123456789',
            'create_admin_form[email]' => 'new@lukaszstaniszewski.pl',
            'create_admin_form[first_name]' => 'First name',
            'create_admin_form[surname]' => 'Surname',
            'create_admin_form[admin_password]' => 'admin1234'
        );

        $form = $buttonCrawlerNode->form($formData);

        $this->admin->submit($form);

        $kernel = self::bootKernel();
        $em = $kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

        $this->assertNotNull(
            $user = $em->getRepository(User::class)
            ->findOneBy(array('username' => 'New_admin'))
        );

        $em->remove($user);
        $em->flush();
    }

    public function testLogoutAction(): void
    {
        $this->admin->request('GET', '/admin1999/log-out');
        $this->assertEquals(302, $this->admin->getResponse()->getStatusCode());
    }

    public function testIndexAction(): void
    {
        $this->admin->request('GET', '/admin1999');
        $this->assertEquals(403, $this->admin->getResponse()->getStatusCode());
    }
}
