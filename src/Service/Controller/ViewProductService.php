<?php


namespace App\Service\Controller;

use App\Entity\ViewProduct;
use App\Model\Device;
use App\Repository\ViewProductRepository;
use App\Service\UserAgent;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ViewProductService
 * @package App\Service\Controller
 */
class ViewProductService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserAgent
     */
    private $userAgent;

    /**
     * @var ViewProductRepository
     */
    private $viewProductRepository;

    /**
     * ViewProductService constructor.
     * @param EntityManagerInterface $em
     * @param UserAgent $userAgent
     * @param ViewProductRepository $viewProductRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        UserAgent $userAgent,
        ViewProductRepository $viewProductRepository
    ) {
        $this->em = $em;
        $this->userAgent = $userAgent;
        $this->viewProductRepository = $viewProductRepository;
    }

    /**
     * @param int $productId
     * @param int|null $userId
     * @throws \Exception
     */
    public function addView(int $productId, ?int $userId = null): void
    {
        $view = new ViewProduct();
        $device = new Device();
        $view->setUserId($userId);
        $view->setProductId($productId);
        $view->setUserIp($this->userAgent->getIp());
        $view->setDevice($device->get($this->userAgent->getUserAgent()));

        $this->em->persist($view);
        $this->em->flush();
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getPopularProducts(int $limit): array
    {
        $popularProducts = $this->viewProductRepository->findPopularProducts($limit);

        if (is_null($popularProducts)) {
            return array();
        }

        return $popularProducts;
    }
}
