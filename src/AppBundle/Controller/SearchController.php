<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function searchAction(Request $request): Response
    {
        $finder = $this->container->get('fos_elastica.finder.fahrradstadt.photo');
        $page = $request->query->getInt('page', 1);
        $query = $request->query->get('query');

        $paginator = $this->get('knp_paginator');
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