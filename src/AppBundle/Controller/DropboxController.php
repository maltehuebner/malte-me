<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\PhotoUploader\PhotoUploader;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Models\FileMetadata;
use Kunnu\Dropbox\Models\ModelCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DropboxController extends Controller
{
    public function authorizeAction(Request $request, UserInterface $user): Response
    {
        $app = $this->getDropboxApp();

        $dropbox = new Dropbox($app);

        $authHelper = $dropbox->getAuthHelper();
        $callbackUrl = $this->generateUrl('dropbox_authorize', [], UrlGeneratorInterface::ABSOLUTE_URL);

        if ($request->query->get('code') && $request->query->get('state')) {
            $accessToken = $authHelper->getAccessToken(
                $request->query->get('code'),
                $request->query->get('state'),
                $callbackUrl
            );

            $token = $accessToken->getToken();

            $session = new Session();
            $session->set('dropboxAccessToken', $token);

            return $this->redirectToRoute('dropbox_import');
        }

        $authUrl = $authHelper->getAuthUrl($callbackUrl);

        return $this->render(
            'AppBundle:Dropbox:authorize.html.twig',
            [
                'authUrl' => $authUrl,
            ]
        );
    }

    public function importAction(Request $request, UserInterface $user): Response
    {
        $app = $this->getDropboxApp();

        $dropbox = new Dropbox($app);

        $listFolderContents = $dropbox->listFolder('/');

        /** @var ModelCollection $items */
        $items = $listFolderContents->getItems();

        $importedPhotoList = [];

        /** @var FileMetadata $fileMetadata */
        foreach ($items as $fileMetadata) {
            $photo = $this->importFile($dropbox, $fileMetadata, $user);

            $dropbox->delete($fileMetadata->getPathLower());

            array_push($importedPhotoList, $photo);
        }

        return $this->render(
            'AppBundle:Dropbox:import.html.twig',
            [
                'importedPhotoList' => $importedPhotoList,
            ]
        );
    }

    protected function getDropboxApp(): DropboxApp
    {
        $session = new Session();
        $accessToken = $session->get('dropboxAccessToken');

        $clientId = $this->getParameter('dropbox.client_id');
        $clientSecret = $this->getParameter('dropbox.client_secret');

        return new DropboxApp($clientId, $clientSecret, $accessToken);
    }

    protected function getFileSuffix(FileMetadata $fileMetadata): string
    {
        $filenameParts = explode('.', $fileMetadata->getName());

        return array_pop($filenameParts);
    }

    protected function importFile(Dropbox $dropbox, FileMetadata $fileMetadata, UserInterface $user): Photo
    {
        $uploadPath = $this->getParameter('upload_destination.photo');
        $tmpFilename = '/tmp/fahrradstadt-dropbox-import';

        $filename = sprintf('%s.%s', uniqid(), $this->getFileSuffix($fileMetadata));
        $path = sprintf('%s/%s', $uploadPath, $filename);

        $file = $dropbox->download($fileMetadata->getPathLower());
        file_put_contents($path, $file->getContents());

        /** @var PhotoUploader $photoUploader */
        $photoUploader = $this->get('app.photo_uploader');

        $photo = new Photo();

        $photo
            ->setUser($user)
            ->setImageName($filename)
            ->setImported(true)
            ->setEnabled(false)
        ;

        $photo = $photoUploader->handleUpload($photo, $user);

        return $photo;
    }
}
