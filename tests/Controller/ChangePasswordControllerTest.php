<?php

namespace App\Tests\Controller;

use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ChangePasswordControllerTest
 * @package App\Tests\Controller
 */
class ChangePasswordControllerTest extends WebTestCase
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
        $this->guess->request('GET', '/change-password');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        $this->user->request('GET', '/change-forgot-password/2b42f823-0b5c-4f68-93c7-081b91ef345d');
        $this->assertEquals(403, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/change-forgot-password/2b42f823-0b5c-4f68-93c7-081b91ef34dd');
        $this->assertEquals(403, $this->user->getResponse()->getStatusCode());

        $this->guess->request('GET', '/change-forgot-password/2b42f823-0b5c-4f68-93c7-081b91ef345d');
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/change-forgot-password/2b42f802-0b5c-4f68-93c7-081b91ef345d');
        $this->assertEquals(404, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/change-password');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/change-password');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());
    }

    public function testChangePassword(): void
    {
        $crawler = $this->user->request('GET', '/change-password');
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('change_password_form[save]');

        $formData = array(
            'change_password_form[plain_password][first]' => 'admin12345',
            'change_password_form[plain_password][second]' => 'admin12345',
            'change_password_form[old_password]' => 'admin1234',
        );

        $form = $buttonCrawlerNode->form($formData);
        $this->user->submit($form);

        $formData = array(
            'change_password_form[plain_password][first]' => 'admin1234',
            'change_password_form[plain_password][second]' => 'admin1234',
            'change_password_form[old_password]' => 'admin12345',
        );

        $form = $buttonCrawlerNode->form($formData);
        $this->user->submit($form);

        /**
         * Administrator
         */

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

    /**
     * @throws \Exception
     */
    public function testChangeForgotPassword(): void
    {
        $kernel = self::bootKernel();
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $repositoryUser = $em->getRepository(User::class);
        $user = $repositoryUser->findOneBy(array('username'=>'ForgotPassword'));

        $forgot = new ForgotPassword();
        $forgot->setUserReferences($user);
        $time = new \DateTime("+ 1hour");
        $time->setTimezone(new \DateTimeZone('Europe/Warsaw'));
        $forgot->setTimeCreate($time);

        $em->persist($forgot);
        $em->flush();

        $crawler = $this->guess->request('GET', '/change-forgot-password/'.$forgot->getToken());
        $buttonCrawlerNode = $crawler->selectButton('new_password_user_form[save]');

        $randNumber = mt_rand(0, 100);
        $formData = array(
            'new_password_user_form[plain_password][first]' => 'testspassword123456'.$randNumber,
            'new_password_user_form[plain_password][second]' => 'testspassword123456'.$randNumber,
        );

        $form = $buttonCrawlerNode->form($formData);
        $this->guess->submit($form);

        $em->clear();
        $userAfterNewPassword = $repositoryUser->findOneBy(array('username'=>'ForgotPassword'));

        $this->assertNotNull($userAfterNewPassword);
        $this->assertNotEquals($userAfterNewPassword->getPassword(), $user->getPassword());
    }
}
