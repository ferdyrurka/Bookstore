<?php

namespace App\Tests\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Tests\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class OrderControllerTest
 * @package App\Tests\Controller
 */
class OrderControllerTest extends WebTestCase
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

    public function testViewCreateOrder(): void
    {
        $this->guess->request('GET', '/cart/add/1/1');
        $cartId = $this->guess->getRequest()->getSession()->get('cart_id');

        $crawler = $this->guess->request('GET', '/order/'.$cartId);
        $this->assertEquals(200, $this->guess->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('#form-create-order'));

        $this->user->request('GET', '/cart/add/1/1');
        $cartId = $this->user->getRequest()->getSession()->get('cart_id');

        $crawler = $this->user->request('GET', '/order/'.$cartId);
        $this->assertEquals(200, $this->user->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('create_order_form[buy]');

        $formData = array(
            'create_order_form[first_name]' => 'Hello',
            'create_order_form[surname]' => 'World',
            'create_order_form[city]' => 'Warsaw',
            'create_order_form[street]' => 'Tadeusza Kosciuszki',
            'create_order_form[house_number]' => '2/2A',
            'create_order_form[post_code]' => '20-200',
            'create_order_form[phone]' => '123456789',
            'create_order_form[email]' => 'luksta556@gmail.com',
            'create_order_form[price_methods][0][price_method_id]' => 1,
            'create_order_form[delivery_methods][0][delivery_method_id]' => 1,
            'create_order_form[other_information]' => 'Hello please a call phone after 17:00.',
        );

        $form = $buttonCrawlerNode->form($formData);

        $this->user->submit($form);


        $kernel = self::bootKernel();

        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $order = $em->getRepository(Order::class);
        $order = $order->findOneBy(array('city' => 'Warsaw', 'street' => 'Tadeusza Kosciuszki'));
        $this->assertNotNull($order);

        $em->remove($order);

        $product = $em->getRepository(Product::class);
        $product = $product->find(1);
        $product->setMagazine(10);

        $em->persist($product);
        $em->flush();
    }
}
