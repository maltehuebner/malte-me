<?php

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Entity\Favorite;
use AppBundle\Entity\Photo;
use AppBundle\Model\CityFrontpageModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends AbstractController
{
    public function indexAction(Request $request, UserInterface $user = null): Response
    {
        $paginator = $this->get('knp_paginator');

        $query = $this->getDoctrine()->getRepository(Photo::class)->getFrontpageQuery($this->getCity($request));

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

        $userFavorites = $this->getDoctrine()->getRepository(Favorite::class)->findForUser($user);

        foreach ($userFavorites as $favorite) {
            $result[$favorite->getPhoto()->getId()] = $favorite;
        }

        return $result;
    }

    public function cityListAction(): Response
    {
        $publicCities = $this->getDoctrine()->getRepository(City::class)->findPublicCities();

        $cityList = [];

        foreach ($publicCities as $publicCity) {
            $frontpageUrl = $this->generateRouteForCity($publicCity, 'frontpage');

            $cityList[] = new CityFrontpageModel($publicCity, $frontpageUrl);
        }

        return $this->render('AppBundle:Includes:footer_city_list.html.twig', [
            'cityList' => $cityList,
        ]);
    }
}
