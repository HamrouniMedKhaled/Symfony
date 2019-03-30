<?php

namespace bonP\reservationBundle\Controller;

use bonP\reservationBundle\Entity\ReservationEvenement;

use bonP\reservationBundle\Form\ReservationEvenementType;
use bonP\userBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ReservationEventController extends Controller
{

    public function CreateReservationEventAction(Request $request,$id)
    {
        $reservationevenement = new ReservationEvenement();

        $em = $this->getDoctrine()->getManager();
        $event=$em->getRepository('bonPenseigneBundle:Evenement')->find($id);

        $form=$this->createForm(ReservationEvenementType::class,$reservationevenement);
        $form->handleRequest($request);
                if ($form->isValid()) {


            $user= $this->container->get('security.token_storage')->getToken()->getUser();
            $reservationevenement->setEvenement($event);
            $reservationevenement->setUser($user);
            $em->persist($reservationevenement);

            $em->flush();
            return $this->redirect($this->generateUrl('_affiche_reservation_event'));

        }
        //}
        return $this->render('@bonPreservation/Reservationevent/create_reservation_event .html.twig', array(
            'form'=>$form->createView(),
            'reservationevenement'=>$reservationevenement,
        ));
    }
    public function CreateReservationEventmAction(Request $request)
    {
        $reservationevenement = new ReservationEvenement();

        $em = $this->getDoctrine()->getManager();
        $event=$em->getRepository('bonPenseigneBundle:Evenement')->find($request->get('id'));
        $user=$em->getRepository(User::class)->find($request->get('user'));

            $reservationevenement->setEvenement($event);
            $reservationevenement->setUser($user);
            $reservationevenement->setNombrplaces($request->get('nbr'));
            $em->persist($reservationevenement);
            $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize(
            array("id"=>$reservationevenement->getId(),
                "nomev"=>$reservationevenement->getEvenement()->getNom(),
                'date'=>$reservationevenement->getEvenement()->getDate(),
                "nbr"=>$reservationevenement->getNombrplaces()));
        return new JsonResponse($formatted);

    }

    public function UpdateReservationEventAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservationEvenement = $em->getRepository('bonPreservationBundle:ReservationEvenement')->find($id);
        $Form = $this->createForm(ReservationEvenementType::class, $reservationEvenement);
        $Form->handleRequest($request);
        if ($Form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($reservationEvenement); //update ... delete
            $em->flush(); //pour execution
            return $this->redirectToRoute('_affiche_reservation_event');


        }

        return $this->render('@bonPreservation/Reservationevent/update_reservation_event .html.twig', array('form' => $Form->createView()
        ));
    }
    public function UpdateReservationEventmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reservationevenement = $em->getRepository('bonPreservationBundle:ReservationEvenement')->find($request->get('id'));
        $reservationevenement->setNombrplaces($request->get('nbr'));

            $em->persist($reservationevenement); //update ... delete
            $em->flush(); //pour execution

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize(
            array("id"=>$reservationevenement->getId(),
                "nomev"=>$reservationevenement->getEvenement()->getNom(),
                'date'=>$reservationevenement->getEvenement()->getDate(),
                "nbr"=>$reservationevenement->getNombrplaces()));
        return new JsonResponse($formatted);




    }

    public function DeleteReservationEventAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $reservationEvenement=$em->getRepository(ReservationEvenement::class)->find($id);
        $em->remove($reservationEvenement);
        $em->flush();
        return $this->redirectToRoute('_affiche_reservation_event');
    }
    public function DeleteReservationEventmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reservationevenement = $em->getRepository(ReservationEvenement::class)->find($request->get('id'));
        $em->remove($reservationevenement);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize(
            array("id"=>$reservationevenement->getId(),
                "nomev"=>$reservationevenement->getEvenement()->getNom(),
                'date'=>$reservationevenement->getEvenement()->getDate(),
                "nbr"=>$reservationevenement->getNombrplaces()));
        return new JsonResponse($formatted);
    }

    public function AfficheReservationEventAction()
    {

        $em = $this->getDoctrine()->getManager();
        $reservationEvenement =$em->getRepository("bonPreservationBundle:ReservationEvenement")->findAll();

        return $this->render('@bonPreservation/Reservationevent/affiche_reservation_event .html.twig', array(
            'reservationEvenements'=>$reservationEvenement
        ));
    }
    public function AfficheReservationEventmAction()
    {

        $em = $this->getDoctrine()->getManager();
        $reservationevenement = $em->getRepository("bonPreservationBundle:ReservationEvenement")->findAll();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $reservationss=array();
        foreach ($reservationevenement as $reservation) {

            $r=array("id"=>$reservation->getId(),
                "nomev"=>$reservation->getEvenement()->getNom(),
                'date'=>$reservation->getEvenement()->getDate(),
                "nbr"=>$reservation->getNombrplaces());
            array_push($reservationss,$r);

        }
        $formatted=$serializer->normalize($reservationss);
        return new JsonResponse($formatted);

    }

}
