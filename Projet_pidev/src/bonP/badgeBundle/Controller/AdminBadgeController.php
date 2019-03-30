<?php

namespace bonP\badgeBundle\Controller;

use bonP\badgeBundle\Entity\Badge;
use bonP\badgeBundle\Form\BadgeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AdminBadgeController extends Controller
{


    public function afficherBadgeAction()
    {

        $em = $this->getDoctrine()->getManager();
        $badges = $em->getRepository(Badge::class)->findAll();

        return $this->render('@bonPbadge/Admin/afficherBadge.html.twig', array('badges' => $badges));
    }

    public function deleteBadgeAction(Badge $badge1)
    {

        $em = $this->getDoctrine()->getManager();
        $badge = $em->getRepository(Badge::class)->find($badge1->getId());
        $id = $badge->getId();
        $em->remove($badge);
        $em->flush();

        return $this->redirectToRoute('admin_afficher_badge', array('id' => $id));

    }

    public function ajoutBadgeAction(Request $request){

        $badge= new Badge();
        $em = $this->getDoctrine()->getManager();
        $badges = $em->getRepository(Badge::class)->findAll();
        $Form=$this->createForm(BadgeType::class, $badge);
        $Form->handleRequest($request);
        if($Form->isValid() && $Form->isSubmitted()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($badge);
            $em->flush();
            return $this->redirectToRoute('Admin_index');

        }
        return $this->render('@bonPbadge/Admin/ajouterBadge.html.twig',array('form'=>$Form->createView()));

    }
}