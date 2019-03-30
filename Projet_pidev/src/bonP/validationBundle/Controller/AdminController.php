<?php
/**
 * Created by PhpStorm.
 * User: veider
 * Date: 2/14/18
 * Time: 4:40 AM
 */

namespace bonP\validationBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('default/AdminLayout.html.twig');
    }

}