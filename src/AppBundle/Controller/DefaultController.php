<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends AbstractController
{
    public function indexAction(Request $request, UserInterface $user = null): Response
    {
        $paginator  = $this->get('knp_paginator');

        $query = $this->getDoctrine()->getRepository('AppBundle:Photo')->getFrontpageQuery($user);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $userFavorites = $this->getFavoritesForUser($user);

        return $this->render('AppBundle:Default:index.html.twig', [
            'pagination' => $pagination,
            'userFavorites' => $userFavorites,
        ]);
    }

    protected function getFavoritesForUser(UserInterface $user = null): array
    {
        $result = [];

        if (!$user) {
            return $result;
        }

        $userFavorites = $this->getDoctrine()->getRepository('AppBundle:Favorite')->findForUser($user);

        foreach ($userFavorites as $favorite) {
            $result[$favorite->getPhoto()->getId()] = $favorite;
        }

        return $result;
    }
}
