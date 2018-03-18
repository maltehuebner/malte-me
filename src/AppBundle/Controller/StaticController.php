<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Seo\SeoPage;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class StaticController extends AbstractController
{
    /**
     * @ParamConverter("city", class="AppBundle:City")
     */
    public function missionAction(City $city, SeoPage $seoPage): Response
    {
        if (!$city->getShowMenuMission() || !$city->getMissionText()) {
            throw $this->createNotFoundException();
        }

        $seoPage
            ->setTitle('Mission')
            ->setDescription('Hamburg wird Fahrradstadt und wir fahren schon mal los! Darum geht’s bei Fahrradstadt.Hamburg')
        ;

        return $this->render('AppBundle:Static:mission.html.twig');
    }
}
