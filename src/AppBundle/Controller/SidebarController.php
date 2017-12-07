<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Favorite;
use AppBundle\Entity\Photo;
use AppBundle\Widget\CalendarWidget\CalendarWidget;
use AppBundle\Widget\CriticalmassWidget\CriticalmassWidget;
use AppBundle\Widget\LuftWidget\LuftWidget;
use AppBundle\Widget\WidgetDataInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class SidebarController extends AbstractController
{
    public function sidebarAction(Request $request, UserInterface $user = null, Photo $photo = null): Response
    {
        $commentList = $this->getDoctrine()->getRepository(Comment::class)->findLatest($this->getCity($request), 10);
        $favouriteList = $this->getDoctrine()->getRepository(Favorite::class)->findLatest($this->getCity($request), 10);

        return $this->render('AppBundle:Sidebar:sidebar.html.twig', [
            'commentList' => $commentList,
            'favouriteList' => $favouriteList,
            'criticalmass' => $this->getCriticalmass($request),
            'calendar' => $this->getCalendar(),
            'luft' => $this->getLuft()
        ]);
    }

    protected function getCriticalmass(Request $request): ?WidgetDataInterface
    {
        $city = $this->getCity($request);

        if (!$city->getCriticalmassCitySlug()) {
            return null;
        }

        /** @var CriticalmassWidget $widget */
        $widget = $this->get(CriticalmassWidget::class);

        return $widget->setCity($city)->render();
    }

    protected function getCalendar(): ?WidgetDataInterface
    {
        /** @var CalendarWidget $widget */
        $widget = $this->get(CalendarWidget::class);
        return $widget->render();
    }

    protected function getLuft(): ?WidgetDataInterface
    {
        /** @var LuftWidget $widget */
        $widget = $this->get(LuftWidget::class);
        return $widget->render();
    }
}
