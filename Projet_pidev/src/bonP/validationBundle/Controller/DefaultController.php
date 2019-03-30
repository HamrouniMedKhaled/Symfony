<?php

namespace bonP\validationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@bonPvalidation/Default/index.html.twig');
    }

    public function mailUsAction(){

        return $this->render('@bonPvalidation/Default/mailUs.html.twig');
    }
    public function loginAction(){

        return $this->render('@bonPvalidation/Default/login.htm.twig');
    }
    public function registeredAction(){

        return $this->render('@bonPvalidation/Default/registered.html.twig');
    }

    public function showPlanAction(){

        return $this->render('default/layoutPlans.html.twig');
    }
}
