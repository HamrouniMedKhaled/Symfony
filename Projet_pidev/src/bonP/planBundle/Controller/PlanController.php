<?php

namespace bonP\planBundle\Controller;

use bonP\planBundle\Entity\Commentaire;
use bonP\planBundle\Entity\Plan;
use bonP\planBundle\Form\CommentaireType;
use bonP\planBundle\Form\PlanEditForm;
use bonP\planBundle\Form\PlanForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlanController extends Controller
{


    public function ajoutAction(Request $request)
    {
        $plan = new Plan();
        $Form = $this->createForm(PlanForm::class, $plan);
        $Form->handleRequest($request);
        if ($Form->isValid()  && $Form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $plan->setUser($user);
            $user->setScore($user->getScore()+20);
            $plan->setActive(false);
            $em->persist($user);
            $em->persist($plan);
            $em->flush();
            return $this->redirectToRoute('mesplans_affichage');

        }
        return $this->render('@plan/Nouveau_plan', array('form' => $Form->createView()));

    }


    public function afficheAction()
    {
        $em = $this->getDoctrine()->getManager();
        $plans = $em->getRepository("planBundle:Plan")->findActivePlans();
        $categories=$em->getRepository("planBundle:Categorie")->findAll();

        return $this->render("@plan/affichage_plans.html.twig", array("plans" => $plans,"categories"=>$categories));
    }


    public function showplanAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = new Commentaire();

        $plan = $em->getRepository("planBundle:Plan")->find($id);
        $currentuser = $this->container->get('security.token_storage')->getToken()->getUser();
        $user=$plan->getUser();
        $ppv=$em->getRepository("planBundle:Plan")->findmostviewedPlans();
        if ($currentuser!=$user){
            $user->setScore($user->getScore()+2);
            $em->persist($user);
            $plan->setScore($plan->getScore() + 1);
            $em->persist($plan);
            $em->flush();
        }

        $commentaire->setPlan($plan);
        $Form = $this->createForm(CommentaireType::class, $commentaire);
        $Form->handleRequest($request);



        $em->persist($plan);
        $em->flush();
        if ($Form->isValid() && $Form->isSubmitted()) {


            $commentaire->setUser($currentuser);
            $plan->addCommentaire($commentaire);
            $em->persist($plan);
            $em->flush();
            return $this->redirectToRoute('plan_affichage', array('id' => $id, "Form" => $Form->createView()));

        }
        return $this->render("@plan/affichage_plan.html.twig", array('plan' => $plan, "Form" => $Form->createView()));

    }



    public function mesplansAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $plans = $em->getRepository("planBundle:Plan")->findBy(array("user" => $user));

        return $this->render("@plan/affichage_mesplans.html.twig", array('plans' => $plans));

    }



    public function deletePLanAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $plan = $em->getRepository('planBundle:Plan')->find($id);
        if ($plan->getUser() == $user) {
            $user->setScore($user->getScore()-100);
            $em->remove($plan);
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('mesplans_affichage'); // affiche
    }






    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $plan = $em->getRepository('planBundle:Plan')->find($id);
        if ($plan->getUser() == $user) {

            $Form = $this->createForm(PlanEditForm::class, $plan);
            $Form->handleRequest($request);
            $image = $plan->getImage();
            if ($Form->isValid() && $Form->isSubmitted()) {
                $plan->setActive(false);
                $em = $this->getDoctrine()->getManager();
                $em->persist($plan); //update ... delete
                $em->flush(); //pour execution


                return $this->redirectToRoute('mesplans_affichage');

            }
            return $this->render('@plan/modif_plan.html.twig', array('form' => $Form->createView(), "image" => $image));
        } else {
            return $this->redirectToRoute('plans_affichage');
        }

    }


    public function indexAction()
    {
        $em=$this->getDoctrine()->getManager();
        $plan=$em->getRepository("planBundle:Plan")->findAll();
        return $this->render('planBundle\Default\index.html.twig',array("plans"=>$plan));
    }





    public function signalerCommentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $commentaire=$em->getRepository('planBundle:Commentaire')->find($id);
        $id=$commentaire->getPlan()->getId();
        if ($commentaire->getUser()!=$user)
        {

            $commentaire->setReportnumber($commentaire->getReportnumber()+1);
            if ($commentaire->getReportnumber()>=2){

                $commentaire->setReported(true);

            }


            $em->persist($commentaire);
            $em->flush();


        }

        return $this->redirectToRoute('plan_affichage',array('id' => $id));



    }


    public function signalerPlanAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $plan=$em->getRepository('planBundle:Plan')->find($id);
        if ($plan->getUser()!=$user)
        {
            $plan->setReportnumber($plan->getReportnumber()+1);
            if ($plan->getReportnumber()>=2){

                $plan->setReported(true);


            }


            $em->persist($plan);
            $em->flush();

        }

        return $this->redirectToRoute('plan_affichage',array('id' => $id));
    }





    public function deleteCommentAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $commentaire=$em->getRepository('planBundle:Commentaire')->find($id);
        $id=$commentaire->getPlan()->getId();
        if ($commentaire->getUser()==$user)
        {


            $em->remove($commentaire);
            $em->flush();


        }

        return $this->redirectToRoute('plan_affichage',array('id' => $id));



    }


    public function afficherparcategorieAction($cat){
        $em = $this->getDoctrine()->getManager();
        $categories=$em->getRepository("planBundle:Categorie")->findAll();
        $plans = $em->getRepository("planBundle:Plan")->findByCategorie($cat);

        return $this->render("@plan/affiche_plan_par_categorie", array("plans" => $plans,"categories"=>$categories,"categ"=>$cat));
    }


    public function afficherpartitreAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $titre = $request->request->get('titre');
        $categories=$em->getRepository("planBundle:Categorie")->findAll();
        $plans = $em->getRepository("planBundle:Plan")->findTitre($titre);

        return $this->render("@plan/affichage_plans.html.twig", array("plans" => $plans,"categories"=>$categories));
    }


}


