<?php
/**
 * Created by PhpStorm.
 * User: morrta
 * Date: 22/02/2018
 * Time: 11:00
 */

namespace bonP\badgeBundle\Manager;


use bonP\badgeBundle\Entity\Badge;
use bonP\badgeBundle\Entity\BadgeUnlock;
use bonP\userBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NoResultException;

class BadgeManager
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * BadgeManager constructor.
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * verifier si le badge existe pour cette action et pour l'occurence de cette action et la debloquer pour l'utilisateur
     * @param User $user
     * @param $action
     * @param $action_count
     */
    public function checkAndUnlock($user, $action, $comments_count){
        //On va appeler cette methode dans comments controller

        try{
            //vérifier si on a un badge qui correspond à action et action_count

            /*$badge = $this->em->getRepository(Badge::class)->findWithUnlockAction($user->getId(),$action,$action_count);

            print_r($badge);
            exit;
            //vérifier si l'utilisateur a déjà ce badge
            if($badge->getUnlocks()->isEmpty()){

                //débloquer ce badge pour l'utilisateur en question
                $unlock = new BadgeUnlock();
                $unlock->setBadge($badge->getId());
                $unlock ->setUser($user->getId());
                $this->em->persist($unlock);
                $this->em->flush();

                //Emetter un evenement pour informer l'application du deblocage
            }*/
            $badges = $this->em ->getRepository(Badge::class)->findAll();

            foreach ($badges as $key=> $badge) {
                $action_count = $badge->getActionCount();
                $nb_comments = count($comments_count);
                if( $nb_comments >= $action_count){
                    $unlock = new BadgeUnlock();
                    $unlock->setBadge($badge);
                    $unlock ->setUser($user);
                    $this->em->persist($unlock);

                }
            }
            $this->em->flush();
        }catch (NoResultException $exception){}



        //humm//'actionName' => $action,
        //humm//'actionCount'=>$action_count
        //humm// if(!empty($badges)){}
        //humm//requete ?! select * from badge b where b.action_cout and b.action_name = 'comment'
    }

    /**
     * get unlocked badge  for user
     * @param User $user
     * @return array
     */
    public function getBadgeFor($user){
        return $this->em->getRepository(Badge::class)->findUnlockerFor($user->getId());

    }
}