<?php

namespace AppBundle\Seo;

use AppBundle\Entity\Photo;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class SeoPage
{
    /** @var SeoPageInterface */
    protected $sonataSeoPage;

    /** @var UploaderHelper $uploaderHelper */
    protected $uploaderHelper;

    /** @var CacheManager $cacheManager */
    protected $cacheManager;

    public function __construct(SeoPageInterface $sonataSeoPage, UploaderHelper $uploaderHelper, CacheManager $cacheManager)
    {
        $this->sonataSeoPage = $sonataSeoPage;
        $this->uploaderHelper = $uploaderHelper;
        $this->cacheManager = $cacheManager;
    }

    public function setTitle(string $title): SeoPage
    {
        $this->sonataSeoPage
            ->setTitle($title)
            ->addMeta('property', 'og:title', $title)
        ;

        return $this;
    }

    public function setDescription(string $description): SeoPage
    {
        $this->sonataSeoPage
            ->addMeta('name', 'description',$description)
            ->addMeta('property', 'og:description', $description)
        ;

        return $this;
    }

    public function setPreviewPhoto(Photo $photo): SeoPage
    {
        $imageFilename = $this->uploaderHelper->asset($photo, 'imageFile');

        $previewPath = $this->cacheManager->getBrowserPath($imageFilename, 'preview');

        $this->sonataSeoPage
            ->addMeta('property', 'og:image', $previewPath)
            ->addMeta('name', 'twitter:image', $previewPath)
            ->addMeta('name', 'twitter:card', 'summary_large_image')
        ;

        return $this;
    }

    public function setCanonicalLink(string $link): SeoPage
    {
        $this->sonataSeoPage
            ->setLinkCanonical($link)
            ->addMeta('property', 'og:url', $link)
        ;

        return $this;
    }
}