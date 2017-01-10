<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PhotoController extends Controller
{
    /**
     * @Route("/upload", name="photo_upload")
     */
    public function uploadAction(Request $request)
    {
        return $this->render('AppBundle:Photo:upload.html.twig');
    }
}
