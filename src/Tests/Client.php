<?php


namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client as ClientTests;

/**
 * Class Login
 * @package App\Tests
 */
class Client extends WebTestCase
{

    private const PARAMETERS = array(
        'HTTP_HOST' => '127.0.0.3',
        'HTTP_USER_AGENT' => 'TESTS',
    );

    /**
     * @return ClientTests
     */
    public function guess(): ClientTests
    {
        $client = self::createClient();

        $client->setServerParameters(self::PARAMETERS);

        return $client;
    }

    /**
     * @return ClientTests
     */
    public function signInUser(): ClientTests
    {
        $client = self::createClient();

        $client->setServerParameters(self::PARAMETERS);

        $crawler = $client->request('GET', '/sign-in');

        $buttonCrawlerNode = $crawler->selectButton('sign_in_form[sign-in]');

        $formData = array(
            'sign_in_form[username]' => 'UserFront',
            'sign_in_form[password]' => 'admin1234'
        );

        $form = $buttonCrawlerNode->form($formData);
        $client->submit($form);

        return $client;
    }

    /**
     * @return ClientTests
     */
    public function signInAdmin(): ClientTests
    {
        $client = self::createClient();

        $client->setServerParameters(self::PARAMETERS);

        $crawler = $client->request('GET', '/admin1999');

        $buttonCrawlerNode = $crawler->selectButton('sign_in_form[sign-in]');

        $formData = array(
            'sign_in_form[username]' => 'Administrator',
            'sign_in_form[password]' => 'admin1234'
        );

        $form = $buttonCrawlerNode->form($formData);
        $client->submit($form);

        return $client;
    }
}
