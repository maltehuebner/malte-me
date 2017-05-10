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
        $feed = $this->buildFeed();

        return new Response($feed);
    }

    protected function buildFeed(bool $includeImages = true): Feed
    {
        $photos = $this->getDoctrine()->getRepository('AppBundle:Photo')->findForFeed();

        $feed = new Feed();

        $channel = $this->createChannel();

        $channel->appendTo($feed);

        /** @var Photo $photo */
        foreach ($photos as $photo) {
            $item = $this->buildItem($photo, $includeImages);

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
            ->url('http://blog.example.com')
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

    protected function createImageMarkdown(Photo $photo): string
    {
        $cacheManager = $this->get('liip_imagine.cache.manager');

        /** @var string */
        $imageUrl = $cacheManager->getBrowserPath($photo->getImageName(), 'preview');

        $imageMarkdown = sprintf('![%s](%s)', $photo->getTitle(), $imageUrl);

        return $imageMarkdown;
    }

    protected function buildItem(Photo $photo, bool $includeImages = true): Item
    {
        $item = new Item();

        $description = $photo->getDescription();

        if ($includeImages) {
            $imageMarkdown = $this->createImageMarkdown($photo);

            $description = sprintf("%s\n\n%s", $imageMarkdown, $description);
        }

        $parsedDescription = $this->parseDescription($description);

        $item
            ->title($photo->getTitle())
            ->description($parsedDescription)
            ->contentEncoded($parsedDescription)
            ->url($this->get('router')->generate('show_photo', ['slug' => $photo->getSlug()]))
            ->author($photo->getUser()->getDisplayname())
            ->pubDate($photo->getDisplayDateTime()->format('U'))
            ->preferCdata(true)
        ;

        return $item;
    }
}
