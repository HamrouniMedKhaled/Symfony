<?php
/**
 * Created by PhpStorm.
 * User: veider
 * Date: 2/21/18
 * Time: 2:09 AM
 */

namespace bonP\dealBundle\Controller;



use bonP\dealBundle\Entity\Deal;

use bonP\dealBundle\Form\DealType;
use bonP\planBundle\Entity\Categorie;
use bonP\planBundle\Entity\Image;
use bonP\userBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use bonP\planBundle\Repository\CategorieRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DealController extends Controller
{
    public function ajouterdealAction (Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $enseigne=$em->getRepository('bonPenseigneBundle:Enseigne')->find($id);
        $deal = new Deal();
        $Form = $this->createForm(DealType::class, $deal);
        $Form->handleRequest($request);
        if ($Form->isValid()&&$Form->isSubmitted()) {
            $datedebut=$request->get('date_dabut');
            $datefin=$request->get('date_fin');
            $deal->setDatedebut( new \DateTime($datedebut));
            $deal->setDatefin( new \DateTime($datefin));
            $deal->setEnseigne($enseigne);
            $em = $this->getDoctrine()->getManager();

            $score=$deal->getScore();
            $manager = $this->get('mgilet.notification');

            $users= $em->getRepository(User::class)->finduscore($score);

            foreach ($users as $u){
                if( ( ($deal->getEnseigne()->getUser()->getId()) != ($u->getId()) ) ) {
                    $notif = $manager->generateNotification('Salut ' . $u->getUsername());
                    $notif->setMessage('un nouveau deal que vous pouvez le payer avec votre score est ajouter');
                    $notif->setLink('http://localhost/BonPlan/projet_pidev/web/app_dev.php/notifications/');

                    $manager->addNotification($u, $notif);
                }
            }
            $em->persist($deal);
            $em->flush();


            return $this->redirectToRoute('Afficher_Mes_Deal');

        }
       $categories=$em->getRepository("planBundle:Categorie")->findAll();
        return $this->render('@bonPdeal/Deal/Ajouter_Deal.html.twig', array('form' => $Form->createView(),'categories'=>$categories));


    }
    public function ajouterdealmAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $enseigne = $em->getRepository('bonPenseigneBundle:Enseigne')->find($request->get('ens'));
        $image=new Image();
        $image->setUrl($request->get('imageurl'));
        $image->setAlt('png');
        $em->persist($image);
        $em->flush();
        $em1 = $this->getDoctrine()->getManager();
        $image1 = $em1->getRepository('planBundle:Image')->findurl($request->get('imageurl'));
        $deal = new Deal();
        $deal->setNom($request->get('nom'));
        $deal->setScore($request->get('score'));
        $deal->setDatefin( new \DateTime ($request->get('datefin')));
        $deal->setDatedebut( new \DateTime($request->get('datedebut')));
        $deal->setDescription($request->get('description'));
        $deal->setPrix($request->get('prix'));
        $deal->setTred($request->get('tred'));
        $deal->setEnseigne($enseigne);
        $deal->setImage($image1[0]);

        $em1->persist($deal);
        $em1->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
        return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize(
            array("id"=>$deal->getId(),
            "nom"=>$deal->getNom(),
            "prix"=>$deal->getPrix(),
            "score"=>$deal->getScore(),
            "tred"=>$deal->getTred(),
            "image_id"=>$deal->getImage()->getId(),
            "image_url"=>$deal->getImage()->getUrl(),
            "enseigne_id"=>$deal->getEnseigne()->getId(),
            "enseigne_capacite"=>$deal->getEnseigne()->getCapacite()));
        return new JsonResponse($formatted);
    }
    public function afficherdealsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $deals = $em->getRepository("bonPdealBundle:Deal")->findActiveDeals($user);
        $categories=$em->getRepository("planBundle:Categorie")->findAll();
        return $this->render("@bonPdeal/Deal/Afficher_Deal.html.twig", array("deals" => $deals,"categories"=>$categories));

    }
    public function afficherdealsmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->find($request->get('user'));
        $deals = $em->getRepository("bonPdealBundle:Deal")->findActivedeals($user);
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $dealss=array();
        foreach ($deals as $deal) {

            $d=array("id" => $deal->getId(),
                "nom" => $deal->getNom(),
                "prix" => $deal->getPrix(),
                "score" => $deal->getScore(),
                "tred" => $deal->getTred(),
                "visite" => $deal->getVisite(),
                "description"=> $deal->getDescription(),
                "image_id" => $deal->getImage()->getId(),
                "image_url" => $deal->getImage()->getUrl(),
                "enseigne_id" => $deal->getEnseigne()->getId(),
                "enseigne_capacite" => $deal->getEnseigne()->getCapacite());
            array_push($dealss,$d);

        }
        $formatted=$serializer->normalize($dealss);
        return new JsonResponse($formatted);

    }
    public function affichermesdealAction(){
        $em=$this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $deals=$em->getRepository("bonPdealBundle:Deal")->findmydeals($user);
        $categories=$em->getRepository("planBundle:Categorie")->findAll();
        return $this->render("@bonPdeal/Deal/Afficher_Deal.html.twig",array('deals'=>$deals,'categories'=>$categories));

    }
    public function affichermesdealmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->find($request->get('user'));
        $deals = $em->getRepository("bonPdealBundle:Deal")->findmydeals($user);
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $dealss=array();
        foreach ($deals as $deal) {

            $d=array("id" => $deal->getId(),
                    "nom" => $deal->getNom(),
                    "prix" => $deal->getPrix(),
                    "score" => $deal->getScore(),
                    "tred" => $deal->getTred(),
                    "visite" => $deal->getVisite(),
                    "description"=> $deal->getDescription(),
                    "image_id" => $deal->getImage()->getId(),
                    "image_url" => $deal->getImage()->getUrl(),
                    "enseigne_id" => $deal->getEnseigne()->getId(),
                    "enseigne_capacite" => $deal->getEnseigne()->getCapacite());
                array_push($dealss,$d);

        }
        $formatted=$serializer->normalize($dealss);


        return new JsonResponse($formatted);
    }
    public function recherchescoredealAction(){
        $em=$this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $deals=$em->getRepository("bonPdealBundle:Deal")->findscore($user->getScore(),$user->getId());
        $categories=$em->getRepository("planBundle:Categorie")->findAll();
        return $this->render("@bonPdeal/Deal/Afficher_Deal.html.twig",array('deals'=>$deals,'categories'=>$categories));

    }
    public function recherchescoredealmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->find($request->get('user'));
        $deals = $em->getRepository("bonPdealBundle:Deal")->findscore($user->getScore(),$user->getId());
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $dealss=array();
        foreach ($deals as $deal) {

            $d=array("id" => $deal->getId(),
                "nom" => $deal->getNom(),
                "prix" => $deal->getPrix(),
                "score" => $deal->getScore(),
                "tred" => $deal->getTred(),
                "visite" => $deal->getVisite(),
                "description"=> $deal->getDescription(),
                "image_id" => $deal->getImage()->getId(),
                "image_url" => $deal->getImage()->getUrl(),
                "enseigne_id" => $deal->getEnseigne()->getId(),
                "enseigne_capacite" => $deal->getEnseigne()->getCapacite());
            array_push($dealss,$d);

        }
        $formatted=$serializer->normalize($dealss);
        return new JsonResponse($formatted);
    }
    public function recherchecategoriedealAction($categorie){
        $em=$this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $deals=$em->getRepository("bonPdealBundle:Deal")->findcategorie($categorie,$user->getId());
        $categories=$em->getRepository("planBundle:Categorie")->findAll();
        return $this->render("@bonPdeal/Deal/Afficher_Deal.html.twig",array('deals'=>$deals,'categories'=>$categories));

    }
    public function recherchecategoriedealmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id=$request->get('id');
        $categorie = $request->get('categorie');
        $deals = $em->getRepository("bonPdealBundle:Deal")->findcategorie($categorie,$id);
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $dealss=array();
        foreach ($deals as $deal) {

            $d=array("id" => $deal->getId(),
                "nom" => $deal->getNom(),
                "prix" => $deal->getPrix(),
                "score" => $deal->getScore(),
                "tred" => $deal->getTred(),
                "visite" => $deal->getVisite(),
                "description"=> $deal->getDescription(),
                "image_id" => $deal->getImage()->getId(),
                "image_url" => $deal->getImage()->getUrl(),
                "enseigne_id" => $deal->getEnseigne()->getId(),
                "enseigne_capacite" => $deal->getEnseigne()->getCapacite());
            array_push($dealss,$d);

        }
        $formatted=$serializer->normalize($dealss);
        return new JsonResponse($formatted);
    }
    public function recherchevilledealAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $ville = $request->request->get('ville');
        $deals=$em->getRepository("bonPdealBundle:Deal")->findville($ville,$user->getId());
        $categories=$em->getRepository("planBundle:Categorie")->findAll();
        return $this->render("@bonPdeal/Deal/Afficher_Deal.html.twig",array('deals'=>$deals,'categories'=>$categories));

    }
    public function recherchevilledealmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id=$request->get('id');
        $ville = $request->get('ville');
        $deals = $em->getRepository("bonPdealBundle:Deal")->findville($ville,$id);
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $dealss=array();
        foreach ($deals as $deal) {

            $d=array("id" => $deal->getId(),
                "nom" => $deal->getNom(),
                "prix" => $deal->getPrix(),
                "score" => $deal->getScore(),
                "tred" => $deal->getTred(),
                "visite" => $deal->getVisite(),
                "description"=> $deal->getDescription(),
                "image_id" => $deal->getImage()->getId(),
                "image_url" => $deal->getImage()->getUrl(),
                "enseigne_id" => $deal->getEnseigne()->getId(),
                "enseigne_capacite" => $deal->getEnseigne()->getCapacite());
            array_push($dealss,$d);

        }
        $formatted=$serializer->normalize($dealss);
        return new JsonResponse($formatted);

    }
    public function afficherdealAction($id){
        $em=$this->getDoctrine()->getManager();
        $deal=$em->getRepository("bonPdealBundle:Deal")->find($id);
        $deal->setVisite($deal->getVisite()+1);
        $em->persist($deal);
        $em->flush();
        return $this->render("@bonPdeal/Deal/Affichage_Deal.html.twig",array('deal'=>$deal));

    }
    public function afficherdealmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deal = $em->getRepository("bonPdealBundle:Deal")->find($request->get('id'));
        $deal->setVisite($deal->getVisite() + 1);
        $em->persist($deal);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);

        $formatted = $serializer->normalize(array("id"=>$deal->getId(),
                                                  "nom"=>$deal->getNom(),
                                                  "prix"=>$deal->getPrix(),
                                                  "score"=>$deal->getScore(),
                                                  "visite" => $deal->getVisite(),
                                                  "description"=> $deal->getDescription(),
                                                  "tred"=>$deal->getTred(),
                                                  "image_id"=>$deal->getImage()->getId(),
                                                  "image_url"=>$deal->getImage()->getUrl(),
                                                  "enseigne_id"=>$deal->getEnseigne()->getId(),
                                                  "enseigne_capacite"=>$deal->getEnseigne()->getCapacite(),
                                                  "categorie"=>$deal->getEnseigne()->getCategorie()->getNom()));


        return new JsonResponse($formatted);
    }
    public function supprimerdealAction ($id){

        $em = $this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $deal=$em->getRepository('bonPdealBundle:Deal')->find($id);
        if ($deal->getEnseigne()->getUser()->getId()==$user->getId()){
            $em->remove($deal);
            $em->flush();
        }

        return $this->redirectToRoute('Afficher_Deals');
    }
    public function supprimerdealmAction (Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $deal = $em->getRepository('bonPdealBundle:Deal')->find($request->get('id'));
        if ($deal->getEnseigne()->getUser()->getId() == $request->get('user')) {
            $em->remove($deal);
            $em->flush();
        }
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize(array("id"=>$deal->getId(),
            "nom"=>$deal->getNom(),
            "prix"=>$deal->getPrix(),
            "score"=>$deal->getScore(),
            "visite" => $deal->getVisite(),
            "description"=> $deal->getDescription(),
            "tred"=>$deal->getTred(),
            "image_id"=>$deal->getImage()->getId(),
            "image_url"=>$deal->getImage()->getUrl(),
            "enseigne_id"=>$deal->getEnseigne()->getId(),
            "enseigne_capacite"=>$deal->getEnseigne()->getCapacite()));
        return new JsonResponse($formatted);
    }
    public function supprimerdealmmAction (Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $deals = $em->getRepository('bonPdealBundle:Deal')->finddate($request->get('dd'));
        foreach ($deals as $deal) {
            $em->remove($deal);
            $em->flush();
        }

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $dealss=array();
        foreach ($deals as $deal) {

            $d=array("id" => $deal->getId(),
                "nom" => $deal->getNom(),
                "prix" => $deal->getPrix(),
                "score" => $deal->getScore(),
                "tred" => $deal->getTred(),
                "visite" => $deal->getVisite(),
                "description"=> $deal->getDescription(),
                "image_id" => $deal->getImage()->getId(),
                "image_url" => $deal->getImage()->getUrl(),
                "enseigne_id" => $deal->getEnseigne()->getId(),
                "enseigne_capacite" => $deal->getEnseigne()->getCapacite());
            array_push($dealss,$d);

        }

        return new JsonResponse($dealss);
    }
    public function modifierdealAction (Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $deal = $em->getRepository('bonPdealBundle:Deal')->find($id);
        if ($deal->getEnseigne()->getUser() == $user) {

            $Form = $this->createForm(DealType::class, $deal);
            $Form->handleRequest($request);
            if ($Form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($deal);
                $em->flush();
                return $this->redirectToRoute('Afficher_Deals');

            }
            return $this->render('@bonPdeal/Deal/Ajouter_Deal.html.twig', array('form' => $Form->createView()));
        } else {
            return $this->redirectToRoute('Afficher_Deals');
        }

    }
    public function modifierdealmAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $deal = $em->getRepository('bonPdealBundle:Deal')->find($request->get('id'));
        if ($deal->getEnseigne()->getUser()->getId() == $request->get('user')) {
            $deal->setNom($request->get('nom'));
            $deal->setScore($request->get('score'));
            $deal->setDatefin(new \DateTime($request->get('datefin')));
            $deal->setDatedebut(new \DateTime($request->get('datedebut')));
            $deal->setDescription($request->get('description'));
            $deal->setPrix($request->get('prix'));
            $deal->setTred($request->get('tred'));
            $em->persist($deal);
            $em->flush();
        }
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize(array("id"=>$deal->getId(),
            "nom"=>$deal->getNom(),
            "prix"=>$deal->getPrix(),
            "score"=>$deal->getScore(),
            "visite" => $deal->getVisite(),
            "description"=> $deal->getDescription(),
            "tred"=>$deal->getTred(),
            "image_id"=>$deal->getImage()->getId(),
            "image_url"=>$deal->getImage()->getUrl(),
            "enseigne_id"=>$deal->getEnseigne()->getId(),
            "enseigne_capacite"=>$deal->getEnseigne()->getCapacite()));
        return new JsonResponse($formatted);

    }

    public function categorieAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository("planBundle:Categorie")->findAll();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $dealss=array();
        foreach ($categories as $deal) {

            $d=array("nom" => $deal->getNom());
            array_push($dealss,$d);

        }
        $formatted=$serializer->normalize($dealss);
        return new JsonResponse($formatted);
    }
}