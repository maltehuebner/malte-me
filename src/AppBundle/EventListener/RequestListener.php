<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\City;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

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

    public function onKernelRequest(GetResponseEvent $event): void
    {
        if ($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser()) {
            $this->assignAnonymousPhotos();
        }

        $this->detectCity($event);
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

    protected function detectCity(GetResponseEvent $event): void
    {
        $hostname = $event->getRequest()->getHost();

        /** @var City $city */
        $city = $this->registry->getRepository(City::class)->findOneByHostname($hostname);

        if (!$city) {
            $event->setResponse(new Response(sprintf('No city for hostname %s found', $hostname), 404));
        }

        $session = new Session();
        $session->set('cityId', $city->getId());
    }
}
