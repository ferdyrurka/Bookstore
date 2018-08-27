<?php


namespace App\Controller;

use App\Security\SessionAttackInterface;
use App\Service\Controller\SearchService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SearchController
 * @package App\Controller
 */
class SearchController extends Controller implements SessionAttackInterface
{
    /**
     * @param Request $request
     * @param SearchService $service
     * @return array
     * @Route("/search", methods={"GET"}, name="index.search")
     * @Template("search/index.html.twig")
     */
    public function indexAction(Request $request, SearchService $service): array
    {
        return array(
            'products' => $service->searchProducts($phrase = $request->get('q')),
            'phrase' => $phrase
        );
    }
}
