<?php

namespace AppBundle\Twig;

use AppBundle\Entity\City;
use AppBundle\Markdown\FahrradstadtMarkdown;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;

class AppExtension extends \Twig_Extension
{
    /** @var City $currentCity */
    protected $currentCity = null;

    /** @var Registry $doctrine */
    protected $doctrine;

    /** @var Session $session */
    protected $session;

    public function __construct(Registry $doctrine, Session $session)
    {
        $this->doctrine = $doctrine;
        $this->session = $session;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('getCity', [$this, 'getCity'])
        ];
    }

    public function getFilters(): array
    {
        return array(
            new \Twig_SimpleFilter('markdown', [$this, 'markdownFilter'], ['is_safe' => ['html']]),
        );
    }

    public function markdownFilter(string $string = null): string
    {
        $parser = new FahrradstadtMarkdown();
        return $parser->parse($string);
    }

    public function getCity(): ?City
    {
        if (!$this->session->has('cityId')) {
            throw new \InvalidArgumentException('City has to be set before calling getCity()');
        }

        if (!$this->currentCity) {
            /** @var int $cityId */
            $cityId = $this->session->get('cityId');

            $this->currentCity = $this->doctrine->getRepository(City::class)->find($cityId);
        }

        return $this->currentCity;
    }

    public function getName()
    {
        return 'app_extension';
    }
}
