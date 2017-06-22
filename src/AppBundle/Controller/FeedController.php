<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use cebe\markdown\Markdown;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FeedController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $feed = $this->buildFeed();

        return new Response($feed);
    }

    protected function buildFeed(): Feed
    {
        $photos = $this->getDoctrine()->getRepository('AppBundle:Photo')->findForFeed();

        $feed = new Feed();

        $channel = $this->createChannel();

        $channel->appendTo($feed);

        /** @var Photo $photo */
        foreach ($photos as $photo) {
            $item = $this->buildItem($photo);

            $item->appendTo($channel);
        }

        return $feed;
    }

    protected function createChannel(): Channel
    {
        $channel = new Channel();

        $channel
            ->title('Fahrradstadt.Hamburg')
            ->description('Fotos und Erlebnisse aus der Fahrradstadt.Hamburg')
            ->url($this->generateUrl('frontpage', [], UrlGeneratorInterface::ABSOLUTE_URL))
            ->language('de_DE')
            ->lastBuildDate((new \DateTime())->format('U'))
            ->ttl(60)
        ;

        return $channel;
    }

    protected function parseDescription(string $description): string
    {
        $parser = new Markdown();

        return $parser->parse($description);
    }

    protected function getImageUrl(Photo $photo): string
    {
        /** @var CacheManager $cacheManager */
        $cacheManager = $this->get('liip_imagine.cache.manager');

        $cacheManager->resolve($photo->getImageName(), 'preview');

        /** @var string */
        $imageUrl = $cacheManager->getBrowserPath($photo->getImageName(), 'preview');

        return $imageUrl;
    }

    protected function buildItem(Photo $photo): Item
    {
        $item = new Item();

        $parsedDescription = $this->parseDescription($photo->getDescription());

        $item
            ->title($photo->getTitle())
            ->description($parsedDescription)
            ->contentEncoded($parsedDescription)
            ->url($this->generateUrl('show_photo', ['slug' => $photo->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL))
            ->author($photo->getUser()->getDisplayname())
            ->pubDate($photo->getDisplayDateTime()->format('U'))
            ->preferCdata(true)
            ->enclosure($this->getImageUrl($photo), 0, 'image/jpeg')
        ;

        return $item;
    }
}
