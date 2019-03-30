<?php

namespace bonP\badgeBundle\Controller;

use bonP\badgeBundle\Entity\Comment;
use bonP\badgeBundle\Entity\Badge;
use bonP\badgeBundle\Entity\BadgeUnlock;
use bonP\badgeBundle\Form\CommentType;
use bonP\badgeBundle\Repository\CommentRepository;
use bonP\dealBundle\Entity\Deal;
use bonP\userBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;



class CommentController extends Controller
{

    public function newAction(Request $request , Deal $deal)
    {

        $em = $this->getDoctrine()->getManager();
        $comment = new  Comment();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $comment->setUser($user);

       $comment->setDeal($deal);

        $form = $this->createForm(CommentType::class , $comment);
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()){

            $em->persist($comment);

            $em->flush();
                //Déblocage du badge
            $comments_count = $em ->getRepository(Comment::class)->countForUser($user->getId());


            $this->get('badge.manager')->checkAndUnlock($user,'comment',$comments_count);
        }

        $comments= $em ->getRepository(Comment::class)->findDealComments($deal->getId());

        $badges = $this->get('badge.manager')->getBadgeFor($user);



        return $this->render('@bonPbadge/Default/new.html.twig', array(

            'deal' => $deal,
            'comments' => $comments,
            'badges' => $badges,
            'form' => $form->createView()

        ));
    }



    public function deleteAction (Comment $comment1){

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $comment = $em->getRepository(Comment::class)->find($comment1->getId());
        $id=$comment1->getDeal()->getId();

        if($comment->getUser()==$user){

            $em->remove($comment);
            $em->flush();
        }
        return $this->redirectToRoute('Afficher_Deal',array('id'=>$id));

    }
    //Mobile tasks
    //testing JSON response : works
    public function getAllCommentsAction(Request $request){

        $em = $this->getDoctrine()->getManager();
       /* dump($deal);
        die();*/
        $deal = $em->getRepository(Deal::class)->find($request->get('bid'));

        $comments = $em->getRepository(Comment::class)->findDealComments($deal);

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $commentss=array();
        foreach ($comments as $comment)
        {
            $c=array("id"=>$comment->getId(),
                "user"=>$comment->getUser()->getUsername(),
                    "content"=>$comment->getContent()

            );
            array_push($commentss,$c);
        }
        $formatted=$serializer->normalize($commentss);

        return new JsonResponse($formatted);


    }

    public function commentMobileAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $comment = new  Comment();


        $user = $em -> getRepository(User::class)->find($request->get('uid'));
        $deal = $em->getRepository(Deal::class)->find($request->get('bid'));


        $comment->setUser($user);

        $comment->setDeal($deal);
        $comment->setContent($request->get('content'));


        $em->persist($comment);
        $em->flush();
        //Déblocage du badge
        $comments_count = $em ->getRepository(Comment::class)->countForUser($user->getId());


        $this->get('badge.manager')->checkAndUnlock($user,'comment',$comments_count);
        //
        //hethy bech tetna7a w nwaliw nraj3ou ken les badges
        $comments= $em ->getRepository(Comment::class)->findDealComments($deal->getId());


        $badges = $this->get('badge.manager')->getBadgeFor($user);



        /*dump($comments);
        die();*/
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted=$serializer->normalize($badges);
        //$badges_list = $formatted;
       // $formatted = $serializer->normalize($comments) ;
       // $comment_list = $formatted;

       // return new JsonResponse( array($badges_list , $comment_list));
        return new JsonResponse($formatted);

    }
    public function getBadgesForUserMobileAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $user = $em -> getRepository(User::class)->find($request->get('uid'));
        //$user = $this->get('security.token_storage')->getToken()->getUser();
        $badges = $this->get('badge.manager')->getBadgeFor($user);
        /*dump($badges);
        die();*/
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted=$serializer->normalize($badges);
        return new JsonResponse($formatted);
    }
    public function getAllBadgesMobileAction(){

        $em = $this->getDoctrine()->getManager();
        $badges = $em -> getRepository(Badge::class)->findAll();
        /*dump($badges);
        die();*/
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted=$serializer->normalize($badges);
        return new JsonResponse($formatted);
    }


// problèmes à fixer : normaliser deux objets , cette solution ignore la première normalisation du deuxième objet
    public function deleteMobileAction (Request $request){

        $em = $this->getDoctrine()->getManager();
        $user = $em -> getRepository(User::class)->find($request->get('uid'));
        $comment = $em->getRepository(Comment::class)->find($request->get('id'));


       // if($comment->getUser()->getUsername()==$user->getUsername()){

            $em->remove($comment);
            $em->flush();
        //}
        /*dump($user->getUsername());
        dump($comment->getUser()->getUsername());
        die();*/
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted=$serializer->normalize($comment);
        return new JsonResponse($formatted);

    }



}
