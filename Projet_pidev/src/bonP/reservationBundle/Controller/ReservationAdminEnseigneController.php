<?php

namespace bonP\reservationBundle\Controller;

use bonP\reservationBundle\Entity\Reservation;
use bonP\reservationBundle\Entity\ReservationEvenement;

use bonP\reservationBundle\Form\ReservationEvenementType;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ReservationAdminEnseigneController extends Controller
{
    public function AfficheReservationEnseigneAction()
    {


        $em = $this->getDoctrine()->getManager();
        $reservations =$em->getRepository(Reservation::class)->findAll();
        return $this->render('@bonPreservation/Admin/afficheEnseigne.html.twig', array(
            'reservations'=>$reservations,
        ));
    }

    public function RechercheReservationEnseigneAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $ens = $request->request->get('ens');
        $reservations=$em->getRepository("bonPreservationBundle:Reservation")->findens($ens);
        return $this->render("@bonPreservation/Admin/afficheEnseigne.html.twig",array('reservations'=>$reservations));

    }
}
