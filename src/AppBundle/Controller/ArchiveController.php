<?php declare(strict_types=1);

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchiveController extends AbstractController
{
    public function archiveAction(Request $request): Response
    {
        $paginator  = $this->get('knp_paginator');
        $page = $request->query->getInt('page', 1);

        $query = $this->getDoctrine()->getRepository('AppBundle:Photo')->getFrontpageQuery($this->getCity($request));

        $pagination = $paginator->paginate(
            $query,
            $page,
            50
        );

        return $this->render('AppBundle:Archive:archive.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
