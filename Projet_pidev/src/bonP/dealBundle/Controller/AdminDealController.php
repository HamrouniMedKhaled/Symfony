<?php

namespace bonP\dealBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use bonP\enseigneBundle\Entity\Enseigne;

class AdminDealController extends Controller
{
    public function afficherAction()
    {
        $em = $this->getDoctrine()->getManager();
        $deals=$em->getRepository("bonPdealBundle:Deal")->findAll();
        return $this->render('@bonPdeal/Admin/AfficherDeal.html.twig',array('deals'=>$deals));
    }
    public function desavtiveAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $deal = $em->getRepository("bonPdealBundle:Deal")->find($id);


        if ($deal->getActive()==1)
        {
            $deal->setActive(false);



            $message = \Swift_Message::newInstance()
                ->setSubject('Desactivation de deal')
                ->setFrom('zanniyassine@gmail.com')
                ->setTo($deal->getEnseigne()->getUser()->getEmail())
                ->setBody($this->renderView('@bonPdeal/Admin/Mail.html.twig',array('deal' => $deal)),'text/html');

            $this->get('mailer')->send($message);


            $em->persist($deal);
            $em->flush();

           return $this->redirectToRoute('Admin_Afficher_Deal');
        }
        else
        {
            return $this->redirectToRoute('Admin_Afficher_Deal');
        }

    }
    public function statisticAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nbrdvs = $em->getRepository("bonPdealBundle:Deal")->nbrdealville();
        $nbrdes = $em->getRepository("bonPdealBundle:Deal")->nbrdealenseigne();
        $nbrrds = $em->getRepository("bonPdealBundle:Reservationdeal")->nbrreservationdeal();
        $nbrres = $em->getRepository("bonPdealBundle:Reservationdeal")->nbrreservationenseigne();
        return $this->render('@bonPdeal/Admin/Statistic.html.twig', array('nbrdvs'=>$nbrdvs,'nbrdes'=>$nbrdes,'nbrrds'=>$nbrrds,'nbrres'=>$nbrres));
    }
}
