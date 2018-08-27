<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Category;
use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminCategoryControllerTest
 * @package App\Tests\Controller\Admin
 */
class AdminCategoryControllerTest extends WebTestCase
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

        $this->guess->request('GET', '/admin1999/categories');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/create-category');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/update-category/2');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/delete-category/2');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->guess->getResponse()->getStatusCode());

        ##Admin

        $this->user->request('GET', '/admin1999/categories');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/create-category');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/update-category/2');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/delete-category/2');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->user->getResponse()->getStatusCode());
    }

    public function testCreateCategoryAction(): void
    {
        $crawler = $this->admin->request('GET', '/admin1999/create-category');
        $this->assertEquals(200, $this->admin->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('category_form[save]');

        $formData = array(
            'category_form[name]' => 'New category',
            'category_form[description]' => 'Hello world',
        );

        $form = $buttonCrawlerNode->form($formData);

        $this->admin->submit($form);

        $kernel = self::bootKernel();
        $id = $kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(Category::class)
            ->findOneBy(array('name'=>'New category'));

        $this->assertNotNull($id);

        $id = $id->getId();

        $this->updateCategoryAction($id);
        $this->deleteCategoryAction($id);
    }

    /**
     * @param int $id
     */
    public function updateCategoryAction(int $id): void
    {
        $crawler = $this->admin->request('GET', '/admin1999/update-category/'.$id);
        $this->assertEquals(200, $this->admin->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('category_form[save]');

        $formData = array(
            'category_form[name]' => 'UpdateCategory',
            'category_form[description]' => 'Hello world',
        );

        $form = $buttonCrawlerNode->form($formData);

        $this->admin->submit($form);

        $crawler = $this->admin->request('GET', '/admin1999/categories');

        $this->assertCount(1, $crawler->filter('th:contains(UpdateCategory)'));
    }

    /**
     * @param int $id
     */
    public function deleteCategoryAction(int $id): void
    {
        $this->admin->request('GET', '/admin1999/delete-category/'.$id);
        $this->assertEquals(302, $this->admin->getResponse()->getStatusCode());

        $crawler = $this->admin->request('GET', '/admin1999/categories');

        $this->assertCount(0, $crawler->filter('th:contains(UpdateCategory)'));
    }

    public function testIndexAction(): void
    {
        $this->admin->request('GET', '/admin1999/categories');
        $this->assertEquals(200, $this->admin->getResponse()->getStatusCode());
    }
}
