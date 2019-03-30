<?php

namespace bonP\reservationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('bonPreservationBundle:Default:index.html.twig');
    }
}
