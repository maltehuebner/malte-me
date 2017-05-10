<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class FeedController extends Controller
{
    public function indexAction(Request $request, UserInterface $user = null): Response
    {
        $photos = $this->getDoctrine()->getRepository('AppBundle:Photo')->findForFeed();

        $feed = new Feed();

        $channel = new Channel();

        $channel
            ->title('Fahrradstadt.Hamburg')
            ->description('Fotos und Erlebnisse aus der Fahrradstadt.Hamburg')
            ->url('http://blog.example.com')
            ->language('de_DE')
            ->lastBuildDate((new \DateTime())->format('U'))
            ->ttl(60)
            ->appendTo($feed);

        /** @var Photo $photo */
        foreach ($photos as $photo) {
            $item = new Item();

            $item
                ->title($photo->getTitle())
                ->description($photo->getDescription())
                ->contentEncoded($photo->getDescription())
                ->url($this->get('router')->generate('show_photo', ['slug' => $photo->getSlug()]))
                ->author($photo->getUser()->getDisplayname())
                ->pubDate($photo->getDisplayDateTime()->format('U'))
                ->preferCdata(true)
                ->appendTo($channel)
            ;
        }

        return new Response($feed);
    }
}
