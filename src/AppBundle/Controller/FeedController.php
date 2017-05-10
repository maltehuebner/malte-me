<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use cebe\markdown\Markdown;
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

        $cacheManager = $this->get('liip_imagine.cache.manager');

        $parser = new Markdown();

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

            /** @var string */
            $imageUrl = $cacheManager->getBrowserPath($photo->getImageName(), 'preview');

            $imageMarkdown = '!['.$photo->getTitle().']('.$imageUrl.')';

            $parsedDescription = $parser->parse($imageMarkdown."\n\n".$photo->getDescription());

            $item
                ->title($photo->getTitle())
                ->description($parsedDescription)
                ->contentEncoded($parsedDescription)
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
