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
use Symfony\Component\Security\Core\User\UserInterface;

class DropboxController extends Controller
{
    public function indexAction(Request $request, UserInterface $user): Response
    {
        $clientId = $this->getParameter('dropbox.client_id');
        $clientSecret = $this->getParameter('dropbox.client_secret');
        $accessToken = $this->getParameter('dropbox.access_token');

        $app = new DropboxApp($clientId, $clientSecret, $accessToken);

        $dropbox = new Dropbox($app);

        $listFolderContents = $dropbox->listFolder('/');

        /** @var ModelCollection $items */
        $items = $listFolderContents->getItems();

        /** @var FileMetadata $item */
        foreach ($items as $item) {
            $photo = $this->importFile($dropbox, $item, $user);
        }

        return new Response();
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
