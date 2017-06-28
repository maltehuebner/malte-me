<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use HWI\Bundle\OAuthBundle\HWIOAuthEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class RequestListener implements EventSubscriberInterface
{
    protected $registry;
    protected $tokenStorage;

    public function __construct(Registry $registry, TokenStorage $tokenStorage)
    {
        $this->registry = $registry;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }

    public function onKernelRequest(Event $event): void
    {
        if ($this->tokenStorage->getToken()->getUser()) {
            $this->assignAnonymousPhotos();
        }
    }

    protected function assignAnonymousPhotos(): void
    {
        if (!$this->tokenStorage->getToken()->getUser() instanceof User) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();

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
