<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    public function targetAction(Request $request): Response
    {
        return $this->redirectToRoute('frontpage');
    }
}