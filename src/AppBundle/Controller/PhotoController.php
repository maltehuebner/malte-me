<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Form\Type\PhotoEditType;
use AppBundle\Form\Type\PhotoLocateType;
use AppBundle\Seo\SeoPage;
use Malenki\Slug;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class PhotoController extends AbstractController
{
    /**
     * @ParamConverter("photo", class="AppBundle:Photo")
     */
    public function embedAction(Photo $photo): Response
    {
        return $this->render('AppBundle:Photo:embed.html.twig', [
            'photo' => $photo,
        ]);
    }

    /**
     * @ParamConverter("photo", class="AppBundle:Photo")
     * @Security("is_granted('view', photo)")
     */
    public function viewAction(SeoPage $seoPage, UserInterface $user = null, Photo $photo): Response
    {
        $comments = $this->getDoctrine()->getRepository('AppBundle:Comment')->findForPhoto($photo);

        $userFavorites = [];

        if ($user) {
            $star = $this->getDoctrine()->getRepository('AppBundle:Favorite')->findForUserAndPhoto($user, $photo);

            if ($star) {
                $userFavorites[$star->getPhoto()->getId()] = $star;
            }
        }

        if ($photo->getEnabled()) {
            $seoPage
                ->setTitle($photo->getTitle())
                ->setPreviewPhoto($photo);

            if ($photo->getDescription()) {
                $seoPage->setDescription($photo->getDescription());
            }
        }

        return $this->render('AppBundle:Photo:view.html.twig', [
            'photo' => $photo,
            'previousPhoto' => $this->getDoctrine()->getRepository(Photo::class )->findPreviousPhoto($photo),
            'nextPhoto' => $this->getDoctrine()->getRepository(Photo::class )->findNextPhoto($photo),
            'comments' => $comments,
            'userFavorites' => $userFavorites,
        ]);
    }

    /**
     * @ParamConverter("photo", class="AppBundle:Photo")
     * @Security("is_granted('edit', photo)")
     */
    public function editAction(Request $request, UserInterface $user, Photo $photo): Response
    {
        $editForm = $this->createForm(PhotoEditType::class, $photo);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Photo $photo */
            $photo = $editForm->getData();

            if ($photo->getImported()) {
                $slug = new Slug($photo->getTitle().' '.$photo->getId());

                $photo
                    ->setSlug($slug->render())
                    ->setEnabled(true)
                ;
            }

            $em->flush();

            return $this->redirectToRoute('show_photo', ['photoSlug' => $photo->getSlug()]);
        }

        return $this->render('AppBundle:Photo:edit.html.twig', [
            'photo' => $photo,
            'editForm' => $editForm->createView(),
        ]);
    }

    /**
     * @ParamConverter("photo", class="AppBundle:Photo")
     * @Security("is_granted('edit', photo)")
     */
    public function locateAction(Request $request, UserInterface $user, Photo $photo): Response
    {
        $editForm = $this->createForm(PhotoLocateType::class, $photo);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Photo $photo */
            $photo = $editForm->getData();

            $em->flush();
        }

        return $this->redirectToRoute('show_photo', ['photoSlug' => $photo->getSlug()]);
    }
}
