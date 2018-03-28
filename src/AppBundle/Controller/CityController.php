<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphNode;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class CityController extends CRUDController
{
    public function facebookPageAction(Request $request, UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        $city = $this->admin->getSubject();

        $form = $this->getPageSelectForm($city, $user);

        if ($request->isMethod(Request::METHOD_POST)) {
            return $this->facebookPagePostAction($request, $user, $city, $form, $entityManager);
        } else {
            return $this->facebookPageGetAction($request, $user, $city, $form, $entityManager);
        }
    }

    protected function facebookPagePostAction(Request $request, UserInterface $user, City $city, FormInterface $form, EntityManagerInterface $entityManager): Response
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var City $city */
            $city = $form->getData();
            $city->setFacebookPageToken($this->getFacebookPageSecretToken($city->getFacebookPageId(), $user));

            $entityManager->flush();
        }

        $this->addFlash('sonata_flash_success', 'Saved facebook page successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    protected function facebookPageGetAction(Request $request, UserInterface $user, City $city, FormInterface $form, EntityManagerInterface $entityManager): Response
    {
        return $this->render('@App/City/select_facebook_page.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    protected function getPageSelectForm(City $city, UserInterface $user): FormInterface
    {
        $form = $this->createFormBuilder($city)
            ->add('facebookPageId', ChoiceType::class, [
                'choices' => $this->getPageList($user),
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        return $form;
    }

    protected function getPageList(UserInterface $user): array
    {
        $fb = $this->getFacebook();

        $endpoint = sprintf('/%d/accounts', $user->getFacebookId());

        $response = $fb->get($endpoint, $user->getFacebookAccessToken());

        $accounts = $response->getGraphEdge();

        $iterator = $accounts->getIterator();

        $pageList = [];

        while ($iterator->current()) {
            /** @var GraphNode $page */
            $page = $iterator->current();

            $pageList[$page->getField('name')] = $page->getField('id');

            $iterator->next();
        }

        return $pageList;
    }

    protected function getFacebookPageSecretToken(string $facebookPageId, UserInterface $user): string
    {
        $fb = $this->getFacebook();

        $endpoint = sprintf('/%d?fields=access_token', $facebookPageId);

        $response = $fb->get($endpoint, $user->getFacebookAccessToken());

        return $response->getGraphNode()->getField('access_token');
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
