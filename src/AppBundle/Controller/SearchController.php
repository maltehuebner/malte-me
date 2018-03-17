<?php declare(strict_types=1);

namespace AppBundle\Controller;

use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AbstractController
{
    public function searchAction(Request $request, Paginator $paginator): Response
    {
        $finder = $this->container->get('fos_elastica.finder.fahrradstadt.photo');
        $page = $request->query->getInt('page', 1);
        $query = $request->query->get('query');

        $results = $finder->createPaginatorAdapter($query);

        $pagination = $paginator->paginate(
            $results,
            $page,
            50
        );

        return $this->render('AppBundle:Search:results.html.twig', [
            'pagination' => $pagination,
            'query' => $query,
        ]);
    }
}
