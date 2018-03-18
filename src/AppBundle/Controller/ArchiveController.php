<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Entity\Photo;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ArchiveController extends AbstractController
{
    /**
     * @ParamConverter("city", class="AppBundle:City")
     */
    public function archiveAction(Request $request, Paginator $paginator, City $city): Response
    {
        $page = $request->query->getInt('page', 1);

        $query = $this->getDoctrine()->getRepository(Photo::class)->getFrontpageQuery($city);

        $pagination = $paginator->paginate(
            $query,
            $page,
            50
        );

        return $this->render('AppBundle:Archive:archive.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
