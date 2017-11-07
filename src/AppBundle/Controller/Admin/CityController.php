<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\City;
use Codebird\Codebird;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;

class CityController extends CRUDController
{
    protected function getCurrentCity(Request $request): City
    {
        $id = $request->get($this->admin->getIdParameter());

        return $this->admin->getObject($id);
    }

    public function twitterAction(Request $request): Response
    {
        $city = $this->getCurrentCity($request);

        $session = $this->getSession();

        $cb = $this->getCodeBird();

        $callbackUrl = $this->admin->generateObjectUrl('twitter_token', $city);

        // get the request token
        $reply = $cb->oauth_requestToken([
            'oauth_callback' => $callbackUrl,
        ]);

        $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
        $session->set('oauth_token', $reply->oauth_token);
        $session->set('oauth_token_secret', $reply->oauth_token_secret);
        $session->set('oauth_verify', true);

        return new RedirectResponse($cb->oauth_authorize());
    }

    public function tokenAction(Request $request): Response
    {
        $session = $this->getSession();

        $cb = $this->getCodeBird();
        $cb->setToken($session->get('oauth_token'), $session->get('oauth_token_secret'));

        $verifier = $request->query->get('oauth_verifier');

        if ($verifier && $session->has('oauth_verify')) {
            $session->remove('oauth_verify');

            $reply = $cb->oauth_accessToken([
                'oauth_verifier' => $verifier
            ]);
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
