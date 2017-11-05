<?php

namespace AppBundle\Controller;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class FacebookController extends Controller
{
    public function postAction(Request $request, UserInterface $user = null): Response
    {
        $fb = new Facebook([
            'app_id' => $this->getParameter('facebook.client_id'),
            'app_secret' => $this->getParameter('facebook.client_secret'),
            'default_graph_version' => 'v2.8',
        ]);

        $linkData = [
            'link' => 'http://www.example.com',
            'message' => 'User provided message',
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->post('/126480444583413/feed', $linkData, $this->getParameter('facebook.access_token'));
        } catch(FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $graphNode = $response->getGraphNode();

        return new Response('Posted with id: ' . $graphNode['id']);
    }
}