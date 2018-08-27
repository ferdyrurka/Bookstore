<?php


namespace App\Controller;

use App\Security\SessionAttackInterface;
use App\Service\Controller\HomeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends Controller implements SessionAttackInterface
{

    /**
     * @param HomeService $service
     * @return array
     * @Route("/", methods={"GET"}, name="home.home")
     * @Template("home/index.html.twig")
     */
    public function indexAction(HomeService $service): array
    {
        return array(
            'popularProducts' => $service->getPopularProducts(6),
            'newProducts' => $service->getNewProducts(9),
        );
    }
}
