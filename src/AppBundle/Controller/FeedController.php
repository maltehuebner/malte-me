<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Entity\Photo;
use cebe\markdown\Markdown;
use Liip\ImagineBundle\Controller\ImagineController;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FeedController extends AbstractController
{
    /**
     * @ParamConverter("city", class="AppBundle:City")
     */
    public function indexAction(City $city): Response
    {
        $feed = $this->buildFeed($city);

        return new Response($feed);
    }

    protected function buildFeed(City $city): Feed
    {
        $photos = $this->getDoctrine()->getRepository(Photo::class)->findForFeed($city);

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
        /** @var UploaderHelper $helper */
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $filename = $helper->asset($photo, 'imageFile');

        /** @var ImagineController */
        $imagine = $this
            ->container
            ->get('liip_imagine.controller');

        /** @var RedirectResponse */
        $imagemanagerResponse = $imagine
            ->filterAction(
                new Request(),
                $filename,
                'preview'
            );

        return $imagemanagerResponse->getTargetUrl();
    }

    protected function buildItem(Photo $photo): Item
    {
        $item = new Item();

        $item
            ->title($photo->getTitle())
            ->url($this->generateUrl('show_photo', ['slug' => $photo->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL))
            ->author($photo->getUser()->getDisplayname())
            ->pubDate($photo->getDisplayDateTime()->format('U'))
            ->preferCdata(true)
            ->enclosure($this->getImageUrl($photo), 0, 'image/jpeg')
        ;

        if ($photo->getDescription()) {
            $parsedDescription = $this->parseDescription($photo->getDescription());

            $item
                ->description($parsedDescription)
                ->contentEncoded($parsedDescription)
            ;
        }


        return $item;
    }
}
