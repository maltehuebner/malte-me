<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FacebookController extends Controller
{
    /**
     * @ParamConverter("city", class="AppBundle:City")
     */
    public function postAction(City $city, UserInterface $user): Response
    {
        $fb = $this->getFacebook();

        $linkData = [
            'link' => 'http://www.example.com',
            'message' => 'User provided message',
        ];

        try {
            $endpoint = sprintf('/%d/feed', $city->getFacebookPageId());

            $response = $fb->post($endpoint, $linkData, $city->getFacebookPageToken());
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

    protected function getFacebook(): Facebook
    {
        return new Facebook([
            'app_id' => $this->getParameter('facebook.client_id'),
            'app_secret' => $this->getParameter('facebook.client_secret'),
            'default_graph_version' => 'v2.8',
        ]);
    }
}
