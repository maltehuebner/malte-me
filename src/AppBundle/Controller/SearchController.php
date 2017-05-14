<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function searchAction(Request $request): Response
    {
        $pagination = [];

        return $this->render('AppBundle:Search:results.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
