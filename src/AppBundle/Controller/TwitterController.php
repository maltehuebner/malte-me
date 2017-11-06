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
        $cb = $this->getCodeBird();

        $reply = $cb->statuses_update('status=Whohoo, I just Tweeted!');

        var_dump($reply);
        return new Response();
    }

    public function authorizeAction(Request $request): Response
    {
        /** @var Session $session */
        $session = $this->get('session');

        $cb = $this->getCodeBird();

        $callbackUrl = $this->generateUrl('twitter_token', [], RouterInterface::ABSOLUTE_URL);

        // get the request token
        $reply = $cb->oauth_requestToken([
            'oauth_callback' => $callbackUrl,
        ]);

        var_dump($reply);
        if ($reply->oauth_callback_confirmed) {
            $this->saveCityAccess($request, $reply);

            return new Response('gespeichert');
        } else {
            return new RedirectResponse($cb->oauth_authorize);
        }
    }

    public function tokenAction(Request $request): Response
    {
        $session = $this->getSession();
        $cb = $this->getCodeBird();

        if ($request->request->has('oauth_verifier') && $session->has('oauth_verify')) {
            $session->remove('oauth_verify');

            $reply = $cb->oauth_accessToken([
                'oauth_verifier' => $session->get('oauth_verifier')
            ]);



            var_dump($reply);
        }

        return new Response('Gespeichert');
    }

    protected function getCodeBird(): Codebird
    {
        Codebird::setConsumerKey($this->getParameter('twitter.client_id'), $this->getParameter('twitter.client_secret'));

        return Codebird::getInstance();
    }

    protected function getSession(): Session
    {
        return $this->get('session');
    }

    protected function saveCityAccess(Request $request, \stdClass $reply): City
    {
        $city = $this->getCity($request);

        $city
            ->setTwitterToken($reply->oauth_token)
            ->setTwitterSecret($reply->oauth_token_secret)
        ;

        $this->getDoctrine()->getManager()->flush();

        return $city;
    }
}
