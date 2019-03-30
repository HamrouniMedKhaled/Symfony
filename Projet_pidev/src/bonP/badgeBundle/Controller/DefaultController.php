<?php

namespace bonP\badgeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('bonPbadgeBundle:Default:index.html.twig');
    }
}
