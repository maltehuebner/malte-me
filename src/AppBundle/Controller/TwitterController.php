<?php

namespace AppBundle\Controller;

use Codebird\Codebird;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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

        if (!$session->get('oauth_token')) {
            // get the request token
            $reply = $cb->oauth_requestToken([
                'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
            ]);

            // store the token
            $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);

            $session->set('oauth_token', $reply->oauth_token);
            $session->set('oauth_token_secret', $reply->oauth_token_secret);
            $session->set('oauth_verify', true);

            // redirect to auth website
            $auth_url = $cb->oauth_authorize();
            header('Location: ' . $auth_url);
            die();

        } elseif ($request->request->has('oauth_verifier') && $session->has('oauth_verify')) {
            // verify the token
            $cb->setToken($session->get('oauth_token'), $session->get('oauth_token_secret'));
            $session->remove('oauth_verify');

            // get the access token
            $reply = $cb->oauth_accessToken([
                'oauth_verifier' => $session->get('oauth_verifier')
            ]);

            // store the token (which is different from the request token!)
            $session->set('oauth_token', $reply->oauth_token);
            $session->set('oauth_token_secret', $reply->oauth_token_secret);

            var_dump($reply);
        }

// assign access token on each page load
        $cb->setToken($session->get('oauth_token'), $session->get('oauth_token_secret'));

        $session->remove('oauth_token');
        $session->remove('oauth_token_secret');

        return new Response('');
    }

    protected function getCodeBird(): Codebird
    {
        Codebird::setConsumerKey($this->getParameter('twitter.client_id'), $this->getParameter('twitter.client_secret'));

        return Codebird::getInstance();
    }
}
