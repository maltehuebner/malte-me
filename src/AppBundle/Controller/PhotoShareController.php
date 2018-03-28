<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Entity\Photo;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\FacebookResponse;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PhotoShareController extends Controller
{
    /**
     * @ParamConverter("city", class="AppBundle:City")
     * @ParamConverter("photo", class="AppBundle:Photo")
     * @Security("is_granted('shareFacebook', photo)")
     */
    public function facebookAction(
        Request $request,
        City $city,
        Photo $photo,
        UserInterface $user,
        LoggerInterface $logger
    ): Response {
        $message = $request->get('photo-share-input');

        if ($message) {
            $this->postToFaceBook($city, $photo, $message, $logger);
        }

        return $this->redirectToRoute('show_photo', [
            'photoSlug' => $photo->getSlug(),
        ]);
    }

    protected function postToFaceBook(City $city, Photo $photo, string $message, LoggerInterface $logger): ?FacebookResponse
    {
        $fb = $this->getFacebook();

        $linkData = [
            'link' => $photo->getShorturl(),
            'message' => $message,
        ];

        try {
            $endpoint = sprintf('/%d/feed', $city->getFacebookPageId());

            $response = $fb->post($endpoint, $linkData, $city->getFacebookPageToken());
        } catch (FacebookResponseException $e) {
            $logger->error($e->getMessage());
        } catch (FacebookSDKException $e) {
            $logger->error($e->getMessage());
        }

        return null;
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
