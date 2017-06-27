<?php

namespace AppBundle\EventListener;

use Doctrine\Bundle\DoctrineBundle\Registry;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use HWI\Bundle\OAuthBundle\HWIOAuthEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LoginListener implements EventSubscriberInterface
{
    protected $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onLogin',
            SecurityEvents::INTERACTIVE_LOGIN => 'onLogin',
            HWIOAuthEvents::CONNECT_COMPLETED => 'onLogin',
        );
    }

    public function onLogin(Event $event): void
    {
        $user = null;

        if ($event instanceof UserEvent) {
            $user = $event->getUser();
        }

        if ($event instanceof InteractiveLoginEvent) {
            $user = $event->getAuthenticationToken()->getUser();
        }

        $session = new Session();
        $photoId = null;
        $photo = null;

        if ($session->has('uploaded_photo_id')) {
            $photoId = $session->get('uploaded_photo_id');
        }

        if ($photoId) {
            $photo = $this->registry->getRepository('AppBundle:Photo')->find($photoId);
        }

        if ($photo) {
            $photo
                ->setUser($user)
                ->setEnabled(true)
            ;

            $this->registry->getManager()->flush();

            $session->remove('uploaded_photo_id');
        }
    }
}
