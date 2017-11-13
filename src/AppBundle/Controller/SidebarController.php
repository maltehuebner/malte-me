<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class SidebarController extends Controller
{
    public function sidebarAction(Request $request, UserInterface $user = null): Response
    {
        return $this->render('AppBundle:Sidebar:sidebar.html.twig');
    }
}
