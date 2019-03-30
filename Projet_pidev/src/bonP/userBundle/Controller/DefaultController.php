<?php

namespace bonP\userBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('bonPuserBundle:Default:index.html.twig');
    }
}
