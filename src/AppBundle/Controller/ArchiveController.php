<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchiveController extends Controller
{
    public function archiveAction(Request $request): Response
    {
        $photoList = $this->getDoctrine()->getRepository('AppBundle:Photo')->findForArchive();

        return $this->render('AppBundle:Archive:archive.html.twig', [
            'photoList' => $photoList
        ]);
    }
}
