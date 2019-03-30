<?php

namespace bonP\reservationBundle\Controller;

use bonP\reservationBundle\Entity\Reservation;
use bonP\reservationBundle\Form\ReservationType;
use bonP\userBundle\Entity\User;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Date;

class ReservationController extends Controller
{
    public function pdfAction(Reservation $reservation){

        $snappy = $this->get("knp_snappy.pdf");
        $snappy->setOption("encoding","UTF-8");
        $filename= "my_first_pdf_file";
        $html = $this->renderView("bonPreservationBundle:Reservation:reservationpdf.html.twig",array(
            "reservation"=>$reservation
        ));
        dump($html);
        return new Response(
            $snappy->getOutputFromHtml($html),200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }

    public function CreateAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();
        $enseigne = $em->getRepository('bonPenseigneBundle:Enseigne')->find($request->get('id'));


        $reservation = new Reservation();
        $Form = $this->createForm(ReservationType::class, $reservation);
        $Form->handleRequest($request);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if ($Form->isValid() && $Form->isSubmitted()) {
            $x = $em->getRepository('bonPreservationBundle:Reservation')->findBy(array('enseigne' => $id, 'datereservation' => $reservation->getDatereservation()));
            $y = 0;
            foreach ($x as $f) {
                $y = $y + $f->getNbplaces();
            }
            if ($enseigne->getCapacite() >= $reservation->getNbplaces() + $y) {


                $reservation->setEnseigne($enseigne);
                // $reservation->getEnseigne()->setCapacite(($reservation->getEnseigne()->getCapacite())-$reservation->getNbplaces());
                $reservation->setUser($user);
                $em->persist($reservation);
                $em->flush();
                $sujet = "reservation";

                $body = "     kubuj";

                $message = \Swift_Message::newInstance()
                    ->setSubject($sujet)
                    ->setFrom('yassinobarkati@gmail.com')
                    ->setTo('yassinobarkati@gmail.com')
                    ->setBody($body);
                $this->get('mailer')->send($message);
                return $this->redirectToRoute('_affiche');
            } else {$mot="on ne peut pass affecté votre reservation car l 'enseigne est plein";

                return $this->render('@bonPreservation/Reservation/create.html.twig', array('form' => $Form->createView(),'mot'=>$mot));
            }
            $reservation = new Reservation();
            $Form = $this->createForm(ReservationType::class, $reservation);
            $Form->handleRequest($request);
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            if ($Form->isValid() && $Form->isSubmitted() && $enseigne->getCapacite() >= $reservation->getNbplaces()) {

                $reservation->setEnseigne($enseigne);
                $reservation->getEnseigne()->setCapacite(($reservation->getEnseigne()->getCapacite()) - $reservation->getNbplaces());
                $reservation->setUser($user);
                $em->persist($reservation);
                $em->flush();
                return $this->redirectToRoute('_affiche');

            }
            return $this->render('@bonPreservation/Reservation/create.html.twig', array('form' => $Form->createView()));


        }
        return $this->render('@bonPreservation/Reservation/create.html.twig', array('form' => $Form->createView()));
    }
    public function CreatemAction(Request $request)

    {

        $em = $this->getDoctrine()->getManager();

        $reservation = new Reservation();
        $enseigne = $em->getRepository('bonPenseigneBundle:Enseigne')->find($request->get('id'));
        $user=$em->getRepository(User::class)->find($request->get('user'));
        $reservation->setEnseigne($enseigne);
        $reservation->setUser($user);
        $reservation->setDatereservation(new \DateTime ($request->get('date')));
        $reservation->setNbplaces($request->get('nbr'));
        $em->persist($reservation);
        $em->flush();


        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize(
            array("id"=>$reservation->getId(),
                "date"=>$reservation->getDatereservation(),
                "nbr"=>$reservation->getNbplaces()));
        return new JsonResponse($formatted);

    }

    public function UpdateAction(Request $request,$id,$nbr=0)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('bonPreservationBundle:Reservation')->find($id);
        $enseigne = $em->getRepository('bonPenseigneBundle:Enseigne')->find($reservation->getEnseigne());
        $Form = $this->createForm(ReservationType::class, $reservation);
        $Form->handleRequest($request);
        $nbr = $reservation->getNbplaces();

        $x = $em->getRepository('bonPreservationBundle:Reservation')->findBy(array('enseigne' => $reservation->getEnseigne(), 'datereservation' => $reservation->getDatereservation()));
        $y = 0;
        foreach ($x as $f) {
            $y = $y + $f->getNbplaces();
        }

        if ($Form->isValid() && $Form->isSubmitted()) {


            $new = $y - $nbr + $reservation->getNbplaces();
            if ($enseigne->getCapacite() >= $new) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($reservation); //update ... delete
                $em->flush(); //pour execution
                return $this->redirectToRoute('_affiche');
            } else {
                var_dump("le");
            }

            if ($Form->isValid() && $Form->isSubmitted()) {
                $enseigne = $reservation->getEnseigne();
                $enseigne->setCapacite((($reservation->getEnseigne()->getCapacite()) - ($reservation->getNbplaces())) + $nbr);
                $em = $this->getDoctrine()->getManager();
                $em->persist($reservation); //update ... delete
                $em->flush(); //pour execution
                return $this->redirectToRoute('_affiche');


            }

            return $this->render('@bonPreservation/Reservation/update.html.twig', array('form' => $Form->createView()
            , 'nbr' => $nbr));
        }
        return $this->render('@bonPreservation/Reservation/update.html.twig', array('form' => $Form->createView()
        , 'nbr' => $nbr));
    }
    public function UpdatemAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('bonPreservationBundle:Reservation')->find($request->get('id'));
        $reservation->setNbplaces($request->get('nbr'));
        $reservation->setDatereservation( new \DateTime ($request->get('date')));
        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation); //update ... delete
        $em->flush(); //pour execution

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize(
            array("id"=>$reservation->getId(),
                "date"=>$reservation->getDatereservation(),
                "nbr"=>$reservation->getNbplaces()));
        return new JsonResponse($formatted);


    }

    public function DeleteAction($id)
    {

        $em=$this->getDoctrine()->getManager();
        $reservation=$em->getRepository(Reservation::class)->find($id);
        $em->remove($reservation);
        $em->flush();
        return $this->redirectToRoute('_affiche');

    }
    public function DeletemAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        $reservation=$em->getRepository(Reservation::class)->find($request->get('id'));
        $em->remove($reservation);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize(
            array("id"=>$reservation->getId(),
                "date"=>$reservation->getDatereservation(),
                "nbr"=>$reservation->getNbplaces()));
        return new JsonResponse($formatted);


    }

    public function AfficheAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $reservation =$em->getRepository("bonPreservationBundle:Reservation")->findAll();

        return $this->render('@bonPreservation/Reservation/affiche.html.twig', array(
            'reservation'=>$reservation
        ));
    }
    public function AffichemAction()
    {

        $em = $this->getDoctrine()->getManager();
        $reservations =$em->getRepository("bonPreservationBundle:Reservation")->findAll();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $reservationss=array();
        foreach ($reservations as $reservation) {

            $r=array("id"=>$reservation->getId(),
                    "date"=>$reservation->getDatereservation(),
                    "nbr"=>$reservation->getNbplaces());
            array_push($reservationss,$r);

        }
        $formatted=$serializer->normalize($reservationss);
        return new JsonResponse($formatted);
    }







}
