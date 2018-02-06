<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Favorite;
use AppBundle\Entity\Photo;
use AppBundle\Widget\CalendarWidget\CalendarModel;
use AppBundle\Widget\CalendarWidget\CalendarWidget;
use AppBundle\Widget\CriticalmassWidget\CriticalmassModel;
use AppBundle\Widget\CriticalmassWidget\CriticalmassWidget;
use AppBundle\Widget\LuftWidget\LuftModel;
use AppBundle\Widget\LuftWidget\LuftWidget;
use AppBundle\Widget\WeatherWidget\WeatherModel;
use AppBundle\Widget\WeatherWidget\WeatherWidget;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class SidebarController extends AbstractController
{
    public function sidebarAction(Request $request, UserInterface $user = null, Photo $photo = null): Response
    {
        $city = $this->getCity($request);

        $commentList = $this->getDoctrine()->getRepository(Comment::class)->findLatest($this->getCity($request), 10);
        $favouriteList = $this->getDoctrine()->getRepository(Favorite::class)->findLatest($this->getCity($request), 10);

        return $this->render('AppBundle:Sidebar:sidebar.html.twig', [
            'commentList' => $commentList,
            'favouriteList' => $favouriteList,
            'criticalmass' => $this->getCriticalmass($city),
            'calendar' => $this->getCalendar($city),
            'luft' => $this->getLuft($city),
            'weather' => $this->getWeather($city)
        ]);
    }

    protected function getCriticalmass(City $city): ?CriticalmassModel
    {
        if (!$city->getCriticalmassCitySlug()) {
            return null;
        }

        /** @var CriticalmassWidget $widget */
        $widget = $this->get(CriticalmassWidget::class);

        return $widget->setCity($city)->render();
    }

    protected function getCalendar(City $city): ?CalendarModel
    {
        /** @var CalendarWidget $widget */
        $widget = $this->get(CalendarWidget::class);
        return $widget->render();
    }

    protected function getLuft(City $city): ?LuftModel
    {
        /** @var LuftWidget $widget */
        $widget = $this->get(LuftWidget::class);
        return $widget->setCity($city)->render();
    }

    protected function getWeather(City $city): ?WeatherModel
    {
        /** @var WeatherWidget $widget */
        $widget = $this->get(WeatherWidget::class);
        return $widget->setCity($city)->render();
    }
}
