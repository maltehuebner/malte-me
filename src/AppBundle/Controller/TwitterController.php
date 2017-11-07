<?php

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use Codebird\Codebird;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;

class TwitterController extends AbstractController
{
    public function postAction(Request $request): Response
    {
        $city = $this->getCity($request);

        $cb = $this->getCodeBird();
        $cb->setToken($city->getTwitterToken(), $city->getTwitterSecret());

        var_dump($city->getTwitterToken(), $city->getTwitterSecret());
        $reply = $cb->statuses_update('status=Whohoo, I just Tweeted!');

        var_dump($reply);
        return new Response();
    }
}
