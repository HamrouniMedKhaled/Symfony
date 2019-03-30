<?php

namespace bonP\enseigneBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@bonPenseigne/Default/index.html.twig');
    }
}
