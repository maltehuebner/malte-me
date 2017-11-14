<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Favorite;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class SidebarController extends AbstractController
{
    public function sidebarAction(Request $request, UserInterface $user = null): Response
    {
        $commentList = $this->getDoctrine()->getRepository(Comment::class)->findLatest($this->getCity($request), 10);
        $favouriteList = $this->getDoctrine()->getRepository(Favorite::class)->findLatest($this->getCity($request), 10);

        return $this->render('AppBundle:Sidebar:sidebar.html.twig', [
            'commentList' => $commentList,
            'favouriteList' => $favouriteList,
        ]);
    }
}
