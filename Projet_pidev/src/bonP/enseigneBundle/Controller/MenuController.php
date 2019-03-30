<?php
/**
 * Created by PhpStorm.
 * User: veider
 * Date: 2/21/18
 * Time: 2:06 AM
 */

namespace bonP\enseigneBundle\Controller;


use bonP\enseigneBundle\Entity\Menu;
use bonP\enseigneBundle\Form\MenuType;
use bonP\enseigneBundle\Form\UpMenuForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller
{
    public function ajoutmenuAction (Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $enseigne=$em->getRepository('bonPenseigneBundle:Enseigne')->find($id);
        $menu = new Menu();
        $Form = $this->createForm(MenuType::class, $menu);
        $Form->handleRequest($request);
        if ($Form->isValid()) {
            $menu->setEnseigne($enseigne);
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();


            return $this->redirectToRoute('bon_pvalidation_homepage');

        }
        return $this->render('@bonPenseigne/Default/ajou_menu.html.twig', array('formss' => $Form->createView()));


    }
    public function affichermenuAction($id){
        $em=$this->getDoctrine()->getManager();
        $menu=$em->getRepository('bonPenseigneBundle:Menu')->find($id);



        return $this->render('@bonPenseigne/Default/aff_menu.html.twig',array('menus'=>$menu));

    }

    public function supprimemenuAction ($id){

        $em = $this->getDoctrine()->getManager();


        $menu=$em->getRepository('bonPenseigneBundle:Menu')->find($id);
        $test=$menu->getEnseigne();

        $em->remove($menu);
        $em->flush();
        $idtest=$test->getId();

        return $this->redirectToRoute('afficher_enseigne',array("id"=>$idtest)); // affiche
    }


    public function updatemenuAction (Request $request,$id){
        $em = $this->getDoctrine()->getManager();

        $menu=$em->getRepository('bonPenseigneBundle:Menu')->find($id);
        $test=$menu->getEnseigne();
            $Form = $this->createForm(UpMenuForm::class, $menu);
            $Form->handleRequest($request);
            if ($Form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($menu); //update ... delete
                $em->flush(); //pour execution
                $idtest=$test->getId();


                return $this->redirectToRoute('afficher_enseigne'  ,array("id"=>$idtest));

            }
            return $this->render('@bonPenseigne/Default/up_menu.html.twig', array('form' => $Form->createView()));
        }

    public function mobileAffichermenuAction($id){
        $em=$this->getDoctrine()->getManager();
        $menu=$em->getRepository('bonPenseigneBundle:Menu')->find($id);
        $response = new Response();
        $response->headers->set("Content-Type","application/json");
        if ($menu!=null)
        {

            $response->setContent(json_encode(
                array(
                    "menu" => array(
                        "id" => $menu->getId(),
                        "contenu" => $menu->getContenu(),
                        "enseigne" => $menu->getEnseigne()->getNom(),
                        "prix" => $menu->getPrix()


                    )
                )
            ));
            return $response;
        }
        return $response->setContent("{\"menu\":{\"id\":0}}");
    }

    public function mobileAfficherAllMenuAction(){
        $em=$this->getDoctrine()->getManager();
        $menuse=$em->getRepository('bonPenseigneBundle:Menu')->findAll();
        $response = new Response();
        $menus=array();

        $response->headers->set("Content-Type","application/json");
        foreach ( $menuse as $menu)
        {   $a=array(
            "id" => $menu->getId(),
            "contenu" => $menu->getContenu(),
            "id_enseigne" => $menu->getEnseigne()->getId(),
            "prix" => $menu->getPrix()

        );
            array_push($menus,$a);
        }
        $response->setContent(json_encode( array(
            "menus" => $menus)));
        return $response;
    }
    public function mobileSupprimermenuAction ($id){
        $response = new Response();

        $em = $this->getDoctrine()->getManager();
        $menu=$em->getRepository('bonPenseigneBundle:Menu')->find($id);

        $em->remove($menu);
        $em->flush();
        return $response->setContent("{\"event\":{\"id\":0}}");

    }

    public function mobileAddMenuAction($contenu,$idenseigne,$prix)
    {
        $em=$this->getDoctrine()->getManager();
        $menu=new Menu();
        $ensigne=$em->getRepository('bonPenseigneBundle:Enseigne')->find($idenseigne);

        $menu->setContenu($contenu);
        $menu->setEnseigne($ensigne);
        $menu->setPrix($prix);

        $em->persist($menu);
        $em->flush();
        $response=new Response();
        $response->setContent("succes");
        return $response;
    }
    public function mobileEditMenuAction($id,$contenu,$idenseigne,$prix)
    {
        $em=$this->getDoctrine()->getManager();
        $menu=new Menu();
        $menu=$em->getRepository('bonPenseigneBundle:Menu')->find($id);
        $ensigne=$em->getRepository('bonPenseigneBundle:Enseigne')->find($idenseigne);

        $menu->setContenu($contenu);
        $menu->setEnseigne($ensigne);
        $menu->setPrix($prix);

        $em->persist($menu);
        $em->flush();
        $response=new Response();
        $response->setContent("succes");
        return $response;
    }

}

