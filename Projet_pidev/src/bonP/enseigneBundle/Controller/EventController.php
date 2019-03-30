<?php
/**
 * Created by PhpStorm.
 * User: veider
 * Date: 2/20/18
 * Time: 11:08 PM
 */

namespace bonP\enseigneBundle\Controller;


use bonP\enseigneBundle\Entity\Evenement;
use bonP\enseigneBundle\Form\EvenementType;
use bonP\enseigneBundle\Form\UpEventForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;

class EventController extends Controller
{
    public function ajouteventAction (Request $request,$id){
        $em = $this->getDoctrine()->getManager();

        $enseigne=$em->getRepository('bonPenseigneBundle:Enseigne')->find($id);
            $event = new Evenement();
            $Form = $this->createForm(EvenementType::class, $event);
            $Form->handleRequest($request);
            if ($Form->isValid()) {
                $event->setEnseigne($enseigne);

                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();


                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('fethi.wuerfelli@gmail.com')
                    ->setTo('fathi.ouerfelli@esprit.tn')
                    ->setBody(
                        $this->renderView(
                        // app/Resources/views/Emails/registration.html.twig
                            'Emails/registration.html.twig',
                            array('name' => $event->getNom())
                        ),
                        'text/html'
                    ) ;

                $this->get('mailer')->send($message);
                return $this->redirectToRoute('bon_pvalidation_homepage');

            }
            return $this->render('@bonPenseigne/Default/ajou_event.html.twig', array('forms' => $Form->createView()));


    }

    public function affichereventAction($id){
        $em=$this->getDoctrine()->getManager();
        $events=$em->getRepository('bonPenseigneBundle:Evenement')->find($id);



        return $this->render("@bonPenseigne/Default/aff_event.html.twig",array('events'=>$events));



    }
    public function affichereventsAction(){
        $em=$this->getDoctrine()->getManager();
        $events=$em->getRepository('bonPenseigneBundle:Evenement')->findAll();



        return $this->render("@bonPenseigne/Default/aff_events.html.twig",array('events'=>$events));



    }



    public function supprimereventAction ($id){

        $em = $this->getDoctrine()->getManager();


        $event=$em->getRepository('bonPenseigneBundle:Evenement')->find($id);
        $test=$event->getEnseigne();

            $em->remove($event);
            $em->flush();
        $idtest=$test->getId();

        return $this->redirectToRoute('afficher_enseigne',array("id"=>$idtest)); // affiche
    }


    public function updateeventAction (Request $request,$id){
        $em = $this->getDoctrine()->getManager();

        $event=$em->getRepository('bonPenseigneBundle:Evenement')->find($id);
        $test=$event->getEnseigne();
        $Form = $this->createForm(UpEventForm::class, $event);
        $Form->handleRequest($request);
        if ($Form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($event); //update ... delete
            $em->flush(); //pour execution
            $idtest=$test->getId();


            return $this->redirectToRoute('afficher_enseigne'  ,array("id"=>$idtest));

        }
        return $this->render('@bonPenseigne/Default/up_event.html.twig', array('form' => $Form->createView()));
    }


    public function mobileAfficherEventAction($id){
        $em=$this->getDoctrine()->getManager();
        $menu=$em->getRepository('bonPenseigneBundle:Evenement')->find($id);
        $response = new Response();
        $response->headers->set("Content-Type","application/json");
        if ($menu!=null)
        {

            $response->setContent(json_encode(
                array(
                    "event" => array(
                        "id" => $menu->getId(),
                        "desc" => $menu->getDescription(),
                        "enseigne" => $menu->getEnseigne()->getId(),
                        "img_id" => $menu->getImage()->getId(),
                        "date" => $menu->getDate()->format("Y-D-M"),
                        "nom" => $menu->getNom()


                    )
                )
            ));
            return $response;
        }
        return $response->setContent("{\"event\":{\"id\":0}}");
    }

    public function mobileAfficherAllEventAction(){
        $em=$this->getDoctrine()->getManager();
        $events=$em->getRepository('bonPenseigneBundle:Evenement')->findAll();
        $response = new Response();
        $eventl=array();

        $response->headers->set("Content-Type","application/json");
        foreach ( $events as $event)
        {   $a=array(
            "id" => $event->getId(),
            "desc" => $event->getDescription(),
            "enseigne" => $event->getEnseigne()->getId(),
            "img_id" => $event->getImage()->getId(),
            "date" => $event->getDate()->format("Y-D-M"),
            "nom" => $event->getNom()

        );
            array_push($eventl,$a);
        }
        $response->setContent(json_encode( array(
            "events" => $eventl)));
        return $response;
    }

    public function mobileSupprimereventAction ($id){
        $response = new Response();

        $em = $this->getDoctrine()->getManager();
        $event=$em->getRepository('bonPenseigneBundle:Evenement')->find($id);

        $em->remove($event);
        $em->flush();
        return $response->setContent("{\"event\":{\"id\":0}}");

    }

    public function mobileAddEventAction($desc,$idenseigne,$nom)
    {
        $em=$this->getDoctrine()->getManager();
        $event=new Evenement();
        $ensigne=$em->getRepository('bonPenseigneBundle:Enseigne')->find($idenseigne);

        $event->setDescription($desc);
        $event->setEnseigne($ensigne);
        $date=new \DateTime();
        $date->format("d/m/y");
        $event->setDate($date);
        $event->setNom($nom);

        $em->persist($event);
        $em->flush();
        $response=new Response();
        $response->setContent("succes");
        return $response;
    }

    public function mobileEditEventAction($id,$desc,$idenseigne,$date,$nom)
    {
        $em=$this->getDoctrine()->getManager();
        $event=new Evenement();
        $ensigne=$em->getRepository('bonPenseigneBundle:Enseigne')->find($idenseigne);
        $event=$em->getRepository('bonPenseigneBundle:Evenement')->find($id);

        $event->setDescription($desc);
        $event->setEnseigne($ensigne);
        $event->setDate($date);
        $event->setNom($nom);

        $em->persist($event);
        $em->flush();
        $response=new Response();
        $response->setContent("succes");
        return $response;
    }








}