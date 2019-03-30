<?php
/**
 * Created by PhpStorm.
 * User: veider
 * Date: 2/15/18
 * Time: 8:53 PM
 */

namespace bonP\planBundle\Controller;


use bonP\planBundle\Entity\Categorie;
use bonP\planBundle\Entity\Commentaire;
use bonP\planBundle\Form\CategorieForm;
use bonP\planBundle\Form\CommentaireType;
use bonP\userBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminPlanController extends Controller
{




    public function ajoutgenreAction(Request $request){
        $genre= new Categorie();
        $Form=$this->createForm(CategorieForm::class, $genre);
        $Form->handleRequest($request);
        if($Form->isValid() && $Form->isSubmitted()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($genre);
            $em->flush();
            return $this->redirectToRoute('Admin_index');

        }
        return $this->render('@plan/Adminajoutergenre',array('form'=>$Form->createView()));

    }



    public function afficheinnactifsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $plans = $em->getRepository("planBundle:Plan")->findInactivePlans();

        return $this->render("@plan/Default/Admininactiveplans", array("plans" => $plans));
    }


    public function afficheAction()
    {
        $em = $this->getDoctrine()->getManager();
        $plans = $em->getRepository("planBundle:Plan")->findActivePlans();

        return $this->render("@plan/Default/Adminactiveplans", array("plans" => $plans));
    }


    public function showplanAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = new Commentaire();

        $plan = $em->getRepository("planBundle:Plan")->find($id);
        $commentaire->setPlan($plan);
        $Form = $this->createForm(CommentaireType::class, $commentaire);
        $Form->handleRequest($request);



        $em->persist($plan);
        $em->flush();
        if ($Form->isValid() && $Form->isSubmitted()) {

            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $commentaire->setUser($user);
            $plan->addCommentaire($commentaire);
            $em->persist($plan);
            $em->flush();
            return $this->redirectToRoute('plan_admin_affichage', array('id' => $id, "Form" => $Form->createView()));

        }
        return $this->render("@plan/Adminaffichage_plans", array('plan' => $plan, "Form" => $Form->createView()));

    }


    public function ActiverDesactiverAction($id){
        $em = $this->getDoctrine()->getManager();
        $manager = $this->get('mgilet.notification');
        $plan = $em->getRepository("planBundle:Plan")->find($id);
        $user= $plan->getUser();
        if ($plan->getActive()==true){
            $plan->setActive(false);

            //notif



                $notif = $manager->generateNotification('Salut ' .$user->getUsername());
                $notif->setMessage('Votre plan à été activé');

                $manager->addNotification($user, $notif);




            $em->persist($plan);
            $em->flush();

            return $this->redirectToRoute('admin_afficher_plans_actifs');
        }
        else{
            $plan->setActive(true);
            $notif = $manager->generateNotification('Salut ' .$user->getUsername());
            $notif->setMessage('Votre plan à été désactivé');

            $manager->addNotification($user, $notif);


            $em->persist($plan);
            $em->flush();
            return $this->redirectToRoute('admin_afficher_plans_inactifs');
        }
    }


    public function deleteCommentAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $commentaire=$em->getRepository('planBundle:Commentaire')->find($id);
        $manager = $this->get('mgilet.notification');
        $id=$commentaire->getPlan()->getId();
        $user=$commentaire->getUser();
        $user->setScore($user->getScore()-20);
        $user->setReport($user->getReport()+1);

        $notif = $manager->generateNotification('Salut ' .$user->getUsername());
        $notif->setMessage('Votre commentaire dans le plan '.$commentaire->getPlan()->getTitre().' a été supprimé');

        $manager->addNotification($user, $notif);

        $em->remove($commentaire);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('admin_afficher_commentaires_reported',array('id' => $id));

    }


    public function afficherrepplansAction()
    {

        $em = $this->getDoctrine()->getManager();
        $plans = $em->getRepository("planBundle:Plan")->findreportedPlans();

        return $this->render("@plan/Default/Admin_afficher_reported_plans", array("plans" => $plans));

    }


    public function afficherreportedcommentsAction(){
        $em = $this->getDoctrine()->getManager();
        $commentaires=$em->getRepository("planBundle:Commentaire")->findreportedcomments();

        return $this->render("@plan/Default/Admin_afficher_reported_comments", array("commentaires" => $commentaires));
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletePLanAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $plan = $em->getRepository('planBundle:Plan')->find($id);


        //Mailer
        $message = \Swift_Message::newInstance()
            ->setSubject('Bonjour')
            ->setFrom('zanniyassine@gmail.com')
            ->setTo($plan->getUser())
            ->setBody(
                $this->renderView(
                    '@plan/Default/Mailer_plan_deleted',
                    array('plan' => $plan)
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);

        $manager = $this->get('mgilet.notification');
        $user=$plan->getUser();
        $notif = $manager->generateNotification('Salut ' .$user->getUsername());
        $notif->setMessage('Votre plan '.$plan->getTitre().' a été supprimé');

        $manager->addNotification($user, $notif);





        $em->remove($plan);
        $em->flush();

        return $this->redirectToRoute('admin_afficher_plans_reported');
    }


    public function resetCommentAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $commentaire=$em->getRepository('planBundle:Commentaire')->find($id);
        $commentaire->setReportnumber(0);
        $commentaire->setReported(false);
        $em->persist($commentaire);
        $em->flush();

        return $this->redirectToRoute('admin_afficher_commentaires_reported');

    }





}