<?php

namespace AppBundle\Controller;

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class DropboxController extends Controller
{
    public function indexAction(Request $request, UserInterface $user = null): Response
    {
        $clientId = $this->getParameter('dropbox.client_id');
        $clientSecret = $this->getParameter('dropbox.client_secret');
        $accessToken = $this->getParameter('dropbox.access_token');

        $app = new DropboxApp($clientId, $clientSecret, $accessToken);

        $dropbox = new Dropbox($app);

        $listFolderContents = $dropbox->listFolder('/');

        $items = $listFolderContents->getItems();

        foreach ($items as $item) {
            $file = $dropbox->download($item->getId());

//File Contents
            $contents = $file->getContents();


//Save file contents to disk
            //file_put_contents(__DIR__ . "/logo.png", $contents);

//Downloaded File Metadata
            $metadata = $file->getMetadata();

//Name
            $metadata->getName();

            var_dump($metadata);
        }

        return new Response();
    }
}
