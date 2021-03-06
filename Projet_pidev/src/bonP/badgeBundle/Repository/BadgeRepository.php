<?php

namespace bonP\badgeBundle\Repository;
use bonP\badgeBundle\Entity\Badge;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * BadgeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BadgeRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $user_id
     * @param $action
     * @param $action_count
     * @return Badge
     */
    public function findWithUnlockAction($user_id, $action, $action_count){

            return $this->createQueryBuilder('b')
                ->where('b.actionName = :action_name')
                ->andWhere('b.actionCount = :action_count OR u.user IS NULL')
                ->andWhere('u.user = :user_id')
                ->leftJoin('b.unlocks', 'u')
                ->select('b , u')
                ->setParameters([

                    'action_count' => $action_count,
                    'action_name'  => $action,
                    'user_id' => $user_id
                ])
                ->getQuery()
                ->getSingleResult();
    }


    /**
     * trouver les badges debloqués ar un utilisateur spécifié
     * @param $user_id
     * @return Badge[]
     */
    public function findUnlockerFor($user_id){


        return $this->createQueryBuilder('b')
            ->join('b.unlocks','u')
            ->where('u.user = :user_id')
            ->setParameter('user_id',$user_id)
            ->getQuery()
            ->getResult();

    }
}
