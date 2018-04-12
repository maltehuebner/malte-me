<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Favorite;
use AppBundle\Entity\Photo;
use AppBundle\Widget\BikeMeterWidget\BikeMeterWidget;
use AppBundle\Widget\CalendarWidget\CalendarWidget;
use AppBundle\Widget\CriticalmassWidget\CriticalmassWidget;
use AppBundle\Widget\LuftWidget\LuftWidget;
use AppBundle\Widget\WeatherWidget\WeatherWidget;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SidebarController extends AbstractController
{
    /**
     * @ParamConverter("city", class="AppBundle:City")
     */
    public function sidebarAction(Request $request, UserInterface $user = null, Photo $photo = null, City $city): Response
    {
        $commentList = $this->getDoctrine()->getRepository(Comment::class)->findLatest($city, 10);
        $favouriteList = $this->getDoctrine()->getRepository(Favorite::class)->findLatest($city, 10);

        return $this->render('AppBundle:Sidebar:sidebar.html.twig', [
            'commentList' => $commentList,
            'favouriteList' => $favouriteList,
        ]);
    }

    public function criticalmassWidgetAction(City $city, CriticalmassWidget $criticalmassWidget): ?Response
    {
        if (!$city->getCriticalmassCitySlug()) {
            return null;
        }

        return $this->render('AppBundle:Sidebar/Widget:criticalmass_widget.html.twig', [
            'criticalmass' =>  $criticalmassWidget->setCity($city)->render(),
        ]);
    }

    public function calendarWidgetAction(CalendarWidget $calendarWidget): ?Response
    {
        return $this->render('AppBundle:Sidebar/Widget:calendar_widget.html.twig', [
            'calendar' => $calendarWidget->render(),
        ]);
    }

    public function luftWidgetAction(City $city, LuftWidget $luftWidget): ?Response
    {
        $luftWidget->setCity($city);

        return $this->render('AppBundle:Sidebar/Widget:luft_widget.html.twig', [
            'luft' => $luftWidget->render(),
        ]);
    }

    public function weatherWidgetAction(City $city, WeatherWidget $weatherWidget): ?Response
    {
        $weatherWidget->setCity($city);

        return $this->render('AppBundle:Sidebar/Widget:weather_widget.html.twig', [
            'weather' => $weatherWidget->render(),
        ]);
    }

    public function bikeMeterWidgetAction(BikeMeterWidget $bikeMeterWidget): ?Response
    {
        return $this->render('AppBundle:Sidebar/Widget:bikemeter_widget.html.twig', [
            'bikeMeter' => $bikeMeterWidget->render(),
        ]);
    }
}
