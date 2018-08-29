<?php

namespace App\Tests\Service\Controller;

use App\Entity\Product;
use App\Entity\ViewProduct;
use App\Repository\ViewProductRepository;
use App\Service\Controller\ViewProductService;
use App\Service\UserAgent;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class ViewProductServiceTest
 * @package App\Tests\Service
 */
class ViewProductServiceTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private $viewProductService;
    private $em;
    private $userAgent;
    private $viewProductRepository;

    public function setUp()
    {

        $this->viewProductRepository = Mockery::mock(ViewProductRepository::class);
        $this->em = Mockery::mock(EntityManagerInterface::class);
        $this->userAgent = Mockery::mock(UserAgent::class);

        $this->viewProductService = new ViewProductService($this->em, $this->userAgent, $this->viewProductRepository);
        parent::setUp();
    }

    /**
     * @throws \Exception
     */
    public function testAddView(): void
    {
        $this->em->shouldReceive('persist')->withArgs(array(ViewProduct::class))->once();
        $this->em->shouldReceive('flush')->once();

        $this->userAgent->shouldReceive('getIp')->andReturn('192.168.1.1')->once();
        $this->userAgent->shouldReceive('getUserAgent')->andReturn('Windows 10')->once();

        $this->assertNull($this->viewProductService->addView(1, 1));
    }

    public function testGetPopularProducts(): void
    {
        $this->viewProductRepository
            ->shouldReceive('findPopularProducts')
            ->withArgs(array(6))
            ->once()
            ->andReturn(array(new Product()))
        ;

        $this->assertNotEmpty($this->viewProductService->getPopularProducts(6, $this->viewProductRepository));

        $this->viewProductRepository
            ->shouldReceive('findPopularProducts')
            ->withArgs(array(6))
            ->once()
            ->andReturn(null)
        ;

        $this->assertEmpty($this->viewProductService->getPopularProducts(6, $this->viewProductRepository));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
