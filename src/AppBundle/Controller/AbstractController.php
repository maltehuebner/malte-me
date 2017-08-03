<?php

namespace AppBundle\Controller;

use AppBundle\Seo\SeoPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractController extends Controller
{
    protected function getSeoPage(): SeoPage
    {
        return $this->get('app.seo_page');
    }
}