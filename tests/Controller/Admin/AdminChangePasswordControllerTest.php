<?php

namespace App\Tests\Controller\Admin;

use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AdminChangePasswordControllerTest
 * @package App\Tests\Controller\Admin
 */
class AdminChangePasswordControllerTest extends WebTestCase
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
        $this->guess->request('GET', '/admin1999/change-password');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/change-password');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());
    }

    public function testChangePasswordAction(): void
    {
        $crawler = $this->admin->request('GET', '/admin1999/change-password');
        $this->assertEquals(200, $this->admin->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('change_password_form[save]');

        $formData = array(
            'change_password_form[plain_password][first]' => 'admin12345',
            'change_password_form[plain_password][second]' => 'admin12345',
            'change_password_form[old_password]' => 'admin1234',
        );

        $form = $buttonCrawlerNode->form($formData);
        $this->admin->submit($form);

        $formData = array(
            'change_password_form[plain_password][first]' => 'admin1234',
            'change_password_form[plain_password][second]' => 'admin1234',
            'change_password_form[old_password]' => 'admin12345',
        );

        $form = $buttonCrawlerNode->form($formData);
        $this->admin->submit($form);
    }
}
