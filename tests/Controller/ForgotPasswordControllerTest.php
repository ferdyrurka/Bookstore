<?php

namespace App\Tests\Controller;

use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ForgotPasswordControllerTest
 * @package App\Tests\Controller
 */
class ForgotPasswordControllerTest extends WebTestCase
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
        $this->user->request('GET', '/forgot-password');
        $this->assertEquals(403, $this->user->getResponse()->getStatusCode());
    }

    public function testIndexAction(): void
    {
        $crawler = $this->guess->request('GET', '/forgot-password');
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('forgot_password_form[save]');

        $formData = array(
            'forgot_password_form[email]' => 'luksta556@gmail.com',
        );

        $form = $buttonCrawlerNode->form($formData);

        $this->guess->submit($form);
    }
}
