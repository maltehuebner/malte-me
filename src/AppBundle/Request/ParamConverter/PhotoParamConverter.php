<?php

namespace AppBundle\Request\ParamConverter;

use AppBundle\Entity\Photo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class PhotoParamConverter extends AbstractParamConverter
{
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $photo = null;

        $photoSlug = $request->get('photoSlug');

        if ($photoSlug) {
            $photo = $this->registry->getRepository(Photo::class)->findOneBySlug($photoSlug);
        }

        if ($photo) {
            $request->attributes->set($configuration->getName(), $photo);
        } else {
            $this->notFound($configuration);
        }
    }
}
