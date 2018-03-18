<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Seo\SeoPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticController extends AbstractController
{
    public function missionAction(Request $request, SeoPage $seoPage): Response
    {
        $city = $this->getCity($request);

        if (!$city || !$city->getShowMenuMission() || !$city->getMissionText()) {
            throw $this->createNotFoundException();
        }

        $seoPage
            ->setTitle('Mission')
            ->setDescription('Hamburg wird Fahrradstadt und wir fahren schon mal los! Darum geht’s bei Fahrradstadt.Hamburg')
        ;

        return $this->render('AppBundle:Static:mission.html.twig');
    }
}
