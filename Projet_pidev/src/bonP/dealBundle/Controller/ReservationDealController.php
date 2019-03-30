<?php

namespace bonP\dealBundle\Controller;


use bonP\dealBundle\Entity\Deal;
use bonP\enseigneBundle\Entity\Enseigne;
use bonP\dealBundle\Entity\Reservationdeal;
use bonP\userBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ReservationDealController extends Controller
{
    public function reserverdealAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();
        $deal=$em->getRepository('bonPdealBundle:Deal')->find($id);
        $nbr= $request->request->get('place');
        $categories=$em->getRepository("planBundle:Categorie")->findAll();
        $reservation = new Reservationdeal();
        if ( $deal->getEnseigne()->getCapacite() >= $nbr)
            {
                $deal->getEnseigne()->setCapacite(($deal->getEnseigne()->getCapacite())-$nbr);
                $user = $this->container->get('security.token_storage')->getToken()->getUser();
                $reservation = new Reservationdeal();
                $reservation->setDeal($deal);
                $reservation->setUser($user);
                $reservation->setNbr($nbr);


                $manager = $this->get('mgilet.notification');

                $responsable= $deal->getEnseigne()->getUser()->getUsername();

                $notif = $manager->generateNotification('Salut ' .$responsable);
                $notif->setMessage('Votre deal ' .$deal->getNom() .' a ete reserver par monsieur' .$user->getUsername());
                $manager->addNotification($deal->getEnseigne()->getUser(), $notif);


                $em->persist($reservation);
                $em->flush();
                return $this->render("@bonPdeal/Reservation/ReserverDeal.htm.twig",array('reservation'=>$reservation,'categories'=>$categories));

            }

        return $this->render("@bonPdeal/Reservation/NonReserver.htm.twig",array('reservation'=>$reservation,'categories'=>$categories));

    }
    public function reserverdealmAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $deal = $em->getRepository('bonPdealBundle:Deal')->find($request->get('deal'));
        $nbr = $request->get('place');

        if ($deal->getEnseigne()->getCapacite() >= $nbr) {
            $deal->getEnseigne()->setCapacite(($deal->getEnseigne()->getCapacite()) - $nbr);
            $user=$em->getRepository(User::class)->find($request->get('user'));
            $reservation = new Reservationdeal();
            $reservation->setDeal($deal);
            $reservation->setUser($user);
            $reservation->setNbr($nbr);
            $em->persist($reservation);
            $em->flush();
        }
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize(
            array("did"=>$deal->getId(),
                "dnom"=>$deal->getNom(),
                "dprix"=>$deal->getPrix(),
                "dscore"=>$deal->getScore(),
                "dvisite" => $deal->getVisite(),
                "ddescription"=> $deal->getDescription(),
                "dtred"=>$deal->getTred(),
                "dimage_id"=>$deal->getImage()->getId(),
                "dimage_url"=>$deal->getImage()->getUrl(),
                "denseigne_id"=>$deal->getEnseigne()->getId(),
                "denseigne_capacite"=>$deal->getEnseigne()->getCapacite(),
                "uid" => $user->getId(),
                "uusername" => $user->getUsername(),
                "uemail" => $user->getEmail(),
                //"utelephone" => $user->getTelephone(),
                "uroles" => $user->getRoles(),
                "uscore" => $user->getScore(),
                "rpayer" => false,
                "rplace" => $nbr,
                "rid" => $reservation->getId()

            ));
        return new JsonResponse($formatted);
    }
    public function suprimerreservationAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $reservation=$em->getRepository('bonPdealBundle:Reservationdeal')->find($id);
        if ($reservation->getUser()->getId()==$user->getId()){
            $reservation->getDeal()->getEnseigne()->setCapacite((($reservation->getDeal()->getEnseigne()->getCapacite())+($reservation->getNbr())));
            if ($reservation->getPayer()==1)
            {
                $user->setScore((($user->getScore())+(($reservation->getNbr())*($reservation->getDeal()->getScore()))));
            }

            $manager = $this->get('mgilet.notification');

            $responsable= $reservation->getUser()->getUsername();

            $notif = $manager->generateNotification('Salut ' .$responsable);
            $notif->setMessage('Monsieur '.$user->getUsername().' a annulé la reservation à votre deal ' .$reservation->getDeal()->getNom() );
            $manager->addNotification($user, $notif);
            $em->remove($reservation);
            $em->flush();
        }

        return $this->redirectToRoute('Afficher_Reserver');
    }
    public function suprimerreservationmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->find($request->get('user'));
        $reservation = $em->getRepository('bonPdealBundle:Reservationdeal')->find($request->get('id'));
        if ($reservation->getUser()->getId() == $user->getId()) {
            $reservation->getDeal()->getEnseigne()->setCapacite((($reservation->getDeal()->getEnseigne()->getCapacite()) + ($reservation->getNbr())));
            if ($reservation->getPayer() == 1) {
                $user->setScore((($user->getScore()) + (($reservation->getNbr()) * ($reservation->getDeal()->getScore()))));
            }
            $em->remove($reservation);
            $em->flush();
        }
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $deal=$reservation->getDeal();
        $formatted = $serializer->normalize(
            array("did"=>$deal->getId(),
            "dnom"=>$deal->getNom(),
            "dprix"=>$deal->getPrix(),
            "dscore"=>$deal->getScore(),
            "dvisite" => $deal->getVisite(),
            "ddescription"=> $deal->getDescription(),
            "dtred"=>$deal->getTred(),
            "dimage_id"=>$deal->getImage()->getId(),
            "dimage_url"=>$deal->getImage()->getUrl(),
            "denseigne_id"=>$deal->getEnseigne()->getId(),
            "denseigne_capacite"=>$deal->getEnseigne()->getCapacite(),
            "uid" => $user->getId(),
            "uusername" => $user->getUsername(),
            "uemail" => $user->getEmail(),
            //"utelephone" => $user->getTelephone(),
            "uroles" => $user->getRoles(),
            "uscore" => $user->getScore(),
            "rpayer" => false,
            "rplace" => $reservation->getNbr(),
            "rid" => $reservation->getId()));
        return new JsonResponse($formatted);
    }
    public function afficherreservationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $reservations = $em->getRepository("bonPdealBundle:Reservationdeal")->findReservation($user->getId());
        $categories = $em->getRepository("planBundle:Categorie")->findAll();
        return $this->render('@bonPdeal/Reservation/AfficherReservation.html.twig', array('reservations' => $reservations, 'categories' => $categories));
    }
    public function afficherreservationmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->find($request->get('user'));
        $reservations = $em->getRepository("bonPdealBundle:Reservationdeal")->findReservation($user->getId());
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $reservationss=array();
        foreach ($reservations as $reservation) {
            $deal=$reservation->getDeal();
            $r=array("did"=>$deal->getId(),
                "dnom"=>$deal->getNom(),
                "dprix"=>$deal->getPrix(),
                "dscore"=>$deal->getScore(),
                "dvisite" => $deal->getVisite(),
                "ddescription"=> $deal->getDescription(),
                "dtred"=>$deal->getTred(),
                "dimage_id"=>$deal->getImage()->getId(),
                "dimage_url"=>$deal->getImage()->getUrl(),
                "denseigne_id"=>$deal->getEnseigne()->getId(),
                "duser_id"=>$deal->getEnseigne()->getUser()->getId(),
                "duser_name"=>$deal->getEnseigne()->getUser()->getUsername(),
                "duser_mail"=>$deal->getEnseigne()->getUser()->getEmail(),
                "denseigne_capacite"=>$deal->getEnseigne()->getCapacite(),
                "uid" => $user->getId(),
                "uusername" => $user->getUsername(),
                "uemail" => $user->getEmail(),
                //"utelephone" => $user->getTelephone(),
                "uroles" => $user->getRoles(),
                "uscore" => $user->getScore(),
                "rpayer" => false,
                "rplace" => $reservation->getNbr(),
                "rid" => $reservation->getId());
            array_push($reservationss,$r);
        }
        $formatted=$serializer->normalize($reservationss);
        return new JsonResponse($formatted);
    }
    public function payerdealAction($id)
     {

        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('bonPdealBundle:Reservationdeal')->find($id);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $categories = $em->getRepository("planBundle:Categorie")->findAll();
        if ( (($reservation->getDeal()->getScore())*($reservation->getNbr())) <= ($user->getScore()) )
        {
            $user->setScore($user->getScore()-(($reservation->getDeal()->getScore())*($reservation->getNbr())));
            $reservation->setPayer(true);

            $manager = $this->get('mgilet.notification');

            $responsable= $reservation->getDeal()->getEnseigne()->getUser()->getUsername();

            $notif = $manager->generateNotification('Salut ' .$responsable);
            $notif->setMessage(' La reservation de monsieur' .$user->getUsername() .' pour le deal '. $reservation->getDeal()->getNom() .' a ete payer par score');
            $manager->addNotification($reservation->getDeal()->getEnseigne()->getUser(), $notif);

            $em->persist($reservation);
            $em->flush();
            return $this->render('@bonPdeal/Reservation/PayerReservation.html.twig', array('categories' => $categories));


        }
    return $this->render('@bonPdeal/Reservation/NonPayer.htm.twig', array('categories' => $categories));


     }
    public function payerdealmAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('bonPdealBundle:Reservationdeal')->find($request->get('id'));
        $user=$em->getRepository(User::class)->find($request->get('user'));
        if ((($reservation->getDeal()->getScore()) * ($reservation->getNbr())) <= ($user->getScore())) {
            $user->setScore($user->getScore() - (($reservation->getDeal()->getScore()) * ($reservation->getNbr())));
            $reservation->setPayer(true);
            $em->persist($reservation);
            $em->flush();
            $message = \Swift_Message::newInstance()
                ->setSubject('Bonjour')
                ->setFrom('zanniyassine@gmail.com')
                ->setTo($reservation->getDeal()->getEnseigne()->getUser()->getEmail())
                ->setBody("salut tt le monede");

            $this->get('mailer')->send($message);
        }
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $deal=$reservation->getDeal();
        $formatted = $serializer->normalize(
            array("did"=>$deal->getId(),
                "dnom"=>$deal->getNom(),
                "dprix"=>$deal->getPrix(),
                "dscore"=>$deal->getScore(),
                "dvisite" => $deal->getVisite(),
                "ddescription"=> $deal->getDescription(),
                "dtred"=>$deal->getTred(),
                "dimage_id"=>$deal->getImage()->getId(),
                "dimage_url"=>$deal->getImage()->getUrl(),
                "denseigne_id"=>$deal->getEnseigne()->getId(),
                "denseigne_capacite"=>$deal->getEnseigne()->getCapacite(),
                "uid" => $user->getId(),
                "uusername" => $user->getUsername(),
                "uemail" => $user->getEmail(),
                //"utelephone" => $user->getTelephone(),
                "uroles" => $user->getRoles(),
                "uscore" => $user->getScore(),
                "rpayer" => false,
                "rplace" => $reservation->getNbr(),
                "rid" => $reservation->getId()));
        return new JsonResponse($formatted);
    }

}
