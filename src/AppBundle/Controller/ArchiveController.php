<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchiveController extends Controller
{
    public function archiveAction(Request $request): Response
    {
        $paginator  = $this->get('knp_paginator');

        $query = $this->getDoctrine()->getRepository('AppBundle:Photo')->getFrontpageQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('AppBundle:Archive:archive.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
