<?php

namespace AppBundle\PhotoUploader;

use AppBundle\Entity\Photo;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Malenki\Slug;
use PHPExif\Reader\Reader;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class PhotoUploader
{
    protected $entityManager;
    protected $uploaderHelper;
    protected $kernelRootDir;
    protected $mailer;

    public function __construct(EntityManager $entityManager, UploaderHelper $uploaderHelper, string $kernelRootDir, \Swift_Mailer $mailer)
    {
        $this->entityManager = $entityManager;
        $this->uploaderHelper = $uploaderHelper;
        $this->kernelRootDir = $kernelRootDir;
        $this->mailer = $mailer;
    }

    public function handleUpload(Photo $photo, User $user = null): Photo
    {
        $photo
            ->setUser($user)
            ->setDisplayDateTime(new \DateTime());

        if (!$user || $user->isModerated()) {
            $photo->setEnabled(false);

            $this->notifyModerator();
        }

        $this->entityManager->persist($photo);
        $this->entityManager->flush(); // first flush to generate id

        $photo
            ->setDateTime($this->getPhotoDateTime($photo))
            ->setSlug($this->createSlug($photo));

        // second flush to save slug
        $this->entityManager->flush();

        return $photo;
    }

    protected function getPhotoDateTime(Photo $photo): \DateTime
    {
        $path = $this->kernelRootDir . '/../web/' . $this->uploaderHelper->asset($photo, 'imageFile');

        try {
            $reader = Reader::factory(Reader::TYPE_NATIVE);

            $exif = $reader->getExifFromFile($path);

            $dateTime = null;
            $dateTimeTmp = null;

            if ($exif) {
                $europeBerlin = new \DateTimeZone('Europe/Berlin');
                $utc = new \DateTimeZone('UTC');

                $dateTimeTmp = $exif->getCreationDate();
            }

            if ($dateTimeTmp) {
                $dateTimeString = $dateTimeTmp->format('Y-m-d H:i:s');

                $dateTime = new \DateTime($dateTimeString, $europeBerlin);
                $dateTime->setTimezone($utc);
            }

            if (!$dateTime || !$exif) {
                $dateTime = new \DateTime();
            }
        } catch (\Exception $e) {
            $dateTime = new \DateTime();
        }

        return $dateTime;
    }

    protected function createSlug(Photo $photo): string
    {
        return new Slug($photo->getTitle().' '.$photo->getId());
    }

    protected function notifyModerator(): void
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Fahrradstadt Hamburg: Moderiere Foto')
            ->setFrom('mail@fahrradstadt.hamburg')
            ->setTo('maltehuebner@gmx.org')
            ->setBody('Bitte moderiere ein neues Foto', 'text/plain')
        ;

        $this->mailer->send($message);
    }
}
