<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Entity\Favorite;
use AppBundle\Entity\Photo;
use AppBundle\Model\CityFrontpageModel;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DefaultController extends AbstractController
{
    /**
     * @ParamConverter("city", class="AppBundle:City")
     */
    public function indexAction(Request $request, UserInterface $user = null, City $city, Paginator $paginator): Response
    {
        $query = $this->getDoctrine()->getRepository(Photo::class)->getFrontpageQuery($city);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $userFavorites = $this->getFavoritesForUser($user);

        return $this->render('AppBundle:Default:index.html.twig', [
            'pagination' => $pagination,
            'userFavorites' => $userFavorites,
        ]);
    }

    protected function getFavoritesForUser(UserInterface $user = null): array
    {
        $result = [];

        if (!$user) {
            return $result;
        }

        $userFavorites = $this->getDoctrine()->getRepository(Favorite::class)->findForUser($user);

        foreach ($userFavorites as $favorite) {
            $result[$favorite->getPhoto()->getId()] = $favorite;
        }

        return $result;
    }

    public function cityListAction(RouterInterface $router): Response
    {
        $publicCities = $this->getDoctrine()->getRepository(City::class)->findPublicCities();

        $cityList = [];

        foreach ($publicCities as $publicCity) {
            $frontpageUrl = $this->generateRouteForCity($router, $publicCity, 'frontpage');

            $cityList[] = new CityFrontpageModel($publicCity, $frontpageUrl);
        }

        return $this->render('AppBundle:Includes:footer_city_list.html.twig', [
            'cityList' => $cityList,
        ]);
    }
}
