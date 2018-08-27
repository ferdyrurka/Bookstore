<?php

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AdminControllerTest
 * @package App\Tests\Controller\Admin
 */
class AdminControllerTest extends WebTestCase
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

        $this->guess->request('GET', '/admin1999/personal-details');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        ##User

        $this->user->request('GET', '/admin1999/personal-details');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());
    }

    public function testPersonalDetails(): void
    {
        $crawler = $this->admin->request('GET', '/admin1999/personal-details');
        $this->assertEquals(200, $this->admin->getResponse()->getStatusCode());

        $formData = array(
            'update_user_form[email]' => 'change@lukaszstaniszewski.pl',
            'update_user_form[first_name]' => 'testuser',
            'update_user_form[surname]' => 'testuser',
            'update_user_form[password]' => 'admin1234',
        );

        $buttonCrawlerNode = $crawler->selectButton('update_user_form[save]');

        $form = $buttonCrawlerNode->form($formData);
        $this->admin->submit($form);

        $kernel = self::bootKernel();
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $repository = $em->getRepository(User::class);

        $user = $repository->findOneBy(array(
            'email' => 'change@lukaszstaniszewski.pl',
            'firstName' => 'testuser',
            'surname' => 'testuser'
        ));

        $this->assertNotNull($user);

        $user->setEmail('kontakt@lukaszstaniszewski.pl');
        $user->setFirstName('admin');
        $user->setSurname('admin');

        $em->persist($user);
        $em->flush();
    }
}
