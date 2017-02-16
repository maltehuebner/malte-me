<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $paginator  = $this->get('knp_paginator');

        $query = $this->getDoctrine()->getRepository('AppBundle:Photo')->getFrontpageQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('AppBundle:Default:index.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
