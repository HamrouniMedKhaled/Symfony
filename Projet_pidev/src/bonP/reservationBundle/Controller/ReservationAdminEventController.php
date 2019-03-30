<?php

namespace bonP\reservationBundle\Controller;

use bonP\reservationBundle\Entity\ReservationEvenement;

use bonP\reservationBundle\Form\ReservationEvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ReservationAdminEventController extends Controller
{
    public function AfficheReservationEventAction()
    {

        $em = $this->getDoctrine()->getManager();
        $reservationEvenements = $em->getRepository("bonPreservationBundle:ReservationEvenement")->findAll();

        return $this->render('@bonPreservation/Admin/afficheEvent.html.twig', array(
            'reservationEvenements'=>$reservationEvenements
        ));
    }
public function RechercheReservationEvenementAction(Request $request)
{
    $em=$this->getDoctrine()->getManager();
    $nomE = $request->request->get('nomE');
    $reservationEvenements=$em->getRepository("bonPreservationBundle:ReservationEvenement")->findeven($nomE);
    return $this->render("@bonPreservation/Admin/afficheEvent.html.twig",array('reservationEvenements'=>$reservationEvenements));

}

    public function filterReservationEventAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
    if($request->get('nomEvent')!= null){
        $user =$em->getRepository("userBundle:User")->findByUsername($request->get('nomEvent'));
        $reservationEvenement =$em->getRepository("bonPreservationBundle:ReservationEvenement")->findByUser($user);
    }
    elseif ($request->get('dateEvent')){
        $reservationEvenement =$em->getRepository("bonPreservationBundle:ReservationEvenement")->findByUser($user);
    }
        $em = $this->getDoctrine()->getManager();
        $reservationEvenement =$em->getRepository("bonPreservationBundle:ReservationEvenement")->findAll();

        return $this->render('@bonPreservation/Reservationevent/affiche_reservation_event .html.twig', array(
            'reservationEvenement'=>$reservationEvenement
        ));
    }
}
