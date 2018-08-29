<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Product;
use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AdminProductControllerTest
 * @package App\Tests\Controller\Admin
 */
class AdminProductControllerTest extends WebTestCase
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

        $this->guess->request('GET', '/admin1999/create-product');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/update-product/1');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/products');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        $this->guess->request('GET', '/admin1999/delete-product/1');
        $this->assertEquals(401, $this->guess->getResponse()->getStatusCode());

        #User

        $this->user->request('GET', '/admin1999/create-product');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/update-product/1');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/products');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());

        $this->user->request('GET', '/admin1999/delete-product/1');
        $this->assertEquals(401, $this->user->getResponse()->getStatusCode());
    }

    public function testCreateProductAction(): void
    {
        $crawler = $this->admin->request('GET', '/admin1999/create-product');
        $this->assertEquals(200, $this->admin->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('product_form[save]');

        $formData = array(
            'product_form[name]' => 'Product name',
            'product_form[price_float]' => 10.10,
            'product_form[magazine]' => 30,
            'product_form[description]' => 'Hello world'
        );

        $form = $buttonCrawlerNode->form($formData);

        $this->admin->submit($form);

        $kernel = self::bootKernel();
        $id = $kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(Product::class)
            ->findOneBy(array('name'=>'Product name'));

        $this->assertNotNull($id);

        $id = $id->getId();

        $this->updateProductAction($id);
        $this->deleteProductAction($id);
    }

    /**
     * @param int $id
     */
    public function updateProductAction(int $id): void
    {
        $crawler = $this->admin->request('GET', '/admin1999/update-product/'.$id);
        $this->assertEquals(200, $this->admin->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('product_form[save]');

        $formData = array(
            'product_form[name]' => 'UpdateProduct',
            'product_form[price_float]' => 10.10,
            'product_form[magazine]' => 30,
            'product_form[description]' => 'Hello world',
        );

        $form = $buttonCrawlerNode->form($formData);

        $this->admin->submit($form);

        $crawler = $this->admin->request('GET', '/admin1999/products');
        $this->assertCount(1, $crawler->filter('th:contains(UpdateProduct)'));
    }

    public function testIndexAction()
    {
        $this->admin->request('GET', '/admin1999/products');
        $this->assertEquals(200, $this->admin->getResponse()->getStatusCode());
    }

    /**
     * @param int $id
     */
    public function deleteProductAction(int $id): void
    {
        $this->admin->request('GET', '/admin1999/delete-product/'.$id);
        $this->assertEquals(302, $this->admin->getResponse()->getStatusCode());

        $crawler = $this->admin->request('GET', '/admin1999/products');
        $this->assertCount(0, $crawler->filter('th:contains(UpdateProduct)'));
    }
}
