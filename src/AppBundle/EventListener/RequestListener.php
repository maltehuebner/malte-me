<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\City;
use AppBundle\Entity\User;
use AppBundle\Seo\SeoPage;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class RequestListener implements EventSubscriberInterface
{
    /** @var Registry $registry */
    protected $registry;

    /** @var TokenStorage $tokenStorage */
    protected $tokenStorage;

    /** @var SeoPage $seoPage */
    protected $seoPage;

    /** @var Session $session */
    protected $session;

    public function __construct(Registry $registry, TokenStorage $tokenStorage, SeoPage $seoPage, Session $session)
    {
        $this->registry = $registry;
        $this->tokenStorage = $tokenStorage;
        $this->seoPage = $seoPage;
        $this->session = $session;
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

        $city = $this->detectCity($event);

        if ($city) {
            $this->initSeoPage($city);
        }
    }

    protected function assignAnonymousPhotos(): void
    {
        if (!$this->tokenStorage->getToken()->getUser() instanceof User) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        $photoId = null;
        $photo = null;

        if ($this->session->has('uploaded_photo_id')) {
            $photoId = $this->session->get('uploaded_photo_id');
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

            $this->session->remove('uploaded_photo_id');
        }
    }

    protected function detectCity(GetResponseEvent $event): ?City
    {
        $hostname = $event->getRequest()->getHost();

        /** @var City $city */
        $city = $this->registry->getRepository(City::class)->findOneByHostname($hostname);

        if ($city) {
            $this->session->set('cityId', $city->getId());

            return $city;
        }

        return null;
    }

    protected function initSeoPage(City $city): void
    {
        $this->seoPage
            ->setTitle($city->getTitle())
            ->setSiteName($city->getTitle())
        ;

        if ($city->getSeoDescription()) {
            $this->seoPage->setDescription($city->getSeoDescription());
        }

        if ($city->getSeoKeywords()) {
            $this->seoPage->setKeywords($city->getSeoKeywords());
        }
    }
}
