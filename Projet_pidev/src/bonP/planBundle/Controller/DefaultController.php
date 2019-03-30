<?php

namespace bonP\planBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('planBundle:Default:index.html.twig');
    }
}
