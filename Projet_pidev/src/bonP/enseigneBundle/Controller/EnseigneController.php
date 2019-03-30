<?php
/**
 * Created by PhpStorm.
 * User: veider
 * Date: 2/20/18
 * Time: 10:58 PM
 */

namespace bonP\enseigneBundle\Controller;


use bonP\enseigneBundle\Entity\Enseigne;
use bonP\enseigneBundle\Form\EnseigneType;
use bonP\enseigneBundle\Form\ModifEnseigneForm;
use bonP\enseigneBundle\Form\UpEnseigneForm;
use bonP\userBundle\Entity\Adresse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\HttpFoundation\Response;
use Zend\Json\Expr;


    class EnseigneController extends Controller
{
    public function ajoutenseigneAction(Request $request){
        $enseigne= new Enseigne();
        $Form=$this->createForm(EnseigneType::class, $enseigne);
        $Form->handleRequest($request);
        if($Form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $enseigne->setUser($user);


            $enseigne->setActive(false);

            $em->persist($enseigne);
            $em->flush();
            return $this->redirectToRoute('bon_pvalidation_homepage');

        }
        return $this->render('@bonPenseigne/Ajouter_enseigne.html.twig',array('form'=>$Form->createView()));

    }



    public function afficherenseigneAction($id){
        $em=$this->getDoctrine()->getManager();
        $enseigne=$em->getRepository("bonPenseigneBundle:Enseigne")->find($id);


        return $this->render("@bonPenseigne/aff_enseigne.html.twig",array("enseigne"=>$enseigne));

    }

    public function mesesenseignesAction(){
        $em=$this->getDoctrine()->getManager();

        $user=$this->container->get("security.token_storage")->getToken()->getUser();
        $enseignes=$em->getRepository("bonPenseigneBundle:Enseigne")->findby(array("user"=>$user));

        return $this->render("@bonPenseigne/Default/aff_meseseigne.html.twig",array('enseigne'=>$enseignes));

    }

    public function supprimerenseigneAction ($id){

            $em = $this->getDoctrine()->getManager();
            $enseigne=$em->getRepository('bonPenseigneBundle:Enseigne')->find($id);


            $em->remove($enseigne);
            $em->flush();


            return $this->redirectToRoute('mes_enseigne'); // affiche

    }

    public function modifierenseigneAction (Request $request,$id){
        $em = $this->getDoctrine()->getManager();

        $enseigne=$em->getRepository('bonPenseigneBundle:Enseigne')->find($id);

        $Form = $this->createForm(UpEnseigneForm::class, $enseigne);
        $Form->handleRequest($request);
        if ($Form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($enseigne); //update ... delete
            $em->flush(); //pour execution



            return $this->redirectToRoute('mes_enseigne'  );

        }
        return $this->render('@bonPenseigne/Default/up_enseigne.html.twig', array('form' => $Form->createView()));



    }

    public function showEnseigneAction()
    {
        $em=$this->getDoctrine()->getManager();
        $enseignes=$em->getRepository("bonPenseigneBundle:Enseigne")->findAll();


        return $this->render('@bonPenseigne/Default/showEnseigne.html.twig',array("enseignes"=>$enseignes));
    }

    public function showEvenementAction()
    {
        $em=$this->getDoctrine()->getManager();
        $events=$em->getRepository("bonPenseigneBundle:Evenement")->findAll();



        return $this->render('@bonPenseigne/Default/showEvenement.html.twig',array("events"=>$events));

    }

    public function statistiqueAction() {



        $yData = array(
            array(
                'labels' => array(
                    'formatter' => new Expr('function () { return this.value + " degrees C" }'),
                    'style' => array('color' => '#AA4643')
                ),
            ),
            array(
                'labels' => array(
                    'formatter' => new Expr('function () { return this.value + " Ideas" }'),
                    'style' => array('color' => '#4572A7')
                ),
                'gridLineWidth' => 0,
                'title' => array(
                    'text' => 'Number of Ideas',
                    'style' => array('color' => '#4572A7')
                ),
            ),
        );



        $categories = array('aaa', 'ART', 'Green', 'Philo', 'Sport', 'Design', 'Food', 'etc');

        $ob = new Highchart();

        $ob->chart->renderTo('container'); // The #id of the div where to render the chart

        $ob->chart->type('column');

        $ob->title->text('Numbre of Ideas by Tag');

        $ob->xAxis->categories($categories);

        $ob->yAxis($yData);

        $ob->legend->enabled(false);

        $formatter = new Expr('function () {

 var unit = {

 "Rainfall": "mm",

 "Temperature": "degrees C"

 }[this.series.name];

 return this.x + ": <b>" + this.y + "</b> " + unit;

 }');

        $emp = $this->getDoctrine()->getEntityManager();
        $query1 = $emp->createQuery('SELECT count(p) FROM  enseigneBundle:enseigne p where p.categorie = :color')->setParameter('color','aaa') ;
        $rt = $query1->getResult();



        $series = array(
            array(
                'name' => 'Number of ideas',
                'type' => 'column',
                'color' => '#df3131',
                'yAxis' => 1,
                'data' => array(intval($rt['0']['1'])),
            ),
        );


        $ob->tooltip->formatter($formatter);

        $ob->series($series);

        return $this->render('@bonPenseigne/Default/statistique.html.twig', array(
            'chart' => $ob
        ));
    }

        public function mobileAfficherEnseigneAction($id){
            $em=$this->getDoctrine()->getManager();
            $enseigne=$em->getRepository('bonPenseigneBundle:Enseigne')->find($id);
            $response = new Response();
            $response->headers->set("Content-Type","application/json");
            if ($enseigne!=null)
            {

                $response->setContent(json_encode(
                    array(
                        "enseigne" => array(
                            "id" => $enseigne->getId(),
                            "nom" => $enseigne->getNom(),
                            "desc" => $enseigne->getDescription(),
                            "active" => $enseigne->getActive(),
                            "capacite" => $enseigne->getCapacite(),
                            "adresse" => $enseigne->getAdresse()->getId(),
                            "img_url" => $enseigne->getImage()->getId(),
                            "categorie" => $enseigne->getCategorie()->getId(),
                            "user" => $enseigne->getUser()->getId()


                        )
                    )
                ));
                return $response;
            }
            return $response->setContent("{\"event\":{\"id\":0}}");
        }

        public function mobileAfficherAllEnseigneAction(){
            $em=$this->getDoctrine()->getManager();
            $enseignese=$em->getRepository('bonPenseigneBundle:Enseigne')->findAll();
            $response = new Response();
            $enseignes=array();

            $response->headers->set("Content-Type","application/json");
            foreach ( $enseignese as $enseigne)
            {   $a=array(
                "id" => $enseigne->getId(),
                "nom" => $enseigne->getNom(),
                "desc" => $enseigne->getDescription(),
                "active" => $enseigne->getActive(),
                "capacite" => $enseigne->getCapacite(),
                "adresse" => $enseigne->getAdresse()->getId(),
                "img_url" => $enseigne->getImage()->getUrl(),
                "categorie" => $enseigne->getCategorie()->getId(),
                "user" => $enseigne->getUser()->getId()


            );
                array_push($enseignes,$a);
            }
            $response->setContent(json_encode( array(
                "enseignes" => $enseignes)));
            return $response;
        }

        public function mobileSupprimerenseigneAction ($id){
            $response = new Response();

            $em = $this->getDoctrine()->getManager();
            $enseigne=$em->getRepository('bonPenseigneBundle:Enseigne')->find($id);

            $em->remove($enseigne);
            $em->flush();
            return $response->setContent("{\"event\":{\"id\":0}}");

        }

        public function mobileAddEnseigneAction($desc,$nom,$pays,$rue,$ville,$codepostale,$capacite,$active,$iduser,$idcategorie)
        {
            $em=$this->getDoctrine()->getManager();
            $enseigne=new Enseigne();
            $categorie=$em->getRepository('planBundle:Categorie')->find($idcategorie);
            $user=$em->getRepository('bonPuserBundle:User')->find($iduser);

            $adresse=new Adresse();
            $adresse->setPays($pays);
            $adresse->setRue($rue);
            $adresse->setVille($ville);
            $adresse->setCodepostal($codepostale);

            $em->persist($adresse);
            $em->flush();

            $enseigne->setDescription($desc);
            $enseigne->setNom($nom);
            $enseigne->setActive($active);
            $enseigne->setAdresse($adresse);
            $enseigne->setCapacite($capacite);
            $enseigne->setCategorie($categorie);
            $enseigne->setActive($active);
            $enseigne->setUser($user);

            $em->persist($enseigne);
            $em->flush();
            $response=new Response();
            $response->setContent("succes");
            return $response;
        }


}