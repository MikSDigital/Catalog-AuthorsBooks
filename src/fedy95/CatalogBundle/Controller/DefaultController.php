<?php

namespace fedy95\CatalogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@fedy95Catalog/Default/index.html.twig');
    }
}
