<?php

namespace TSEP\Bundle\SearchBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * Find matching searches.
     *
     * @Route("/get/{profile}", name="search_get")
     * @Template()
     */
    public function getAction ($profile) {}
}
