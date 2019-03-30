<?php
/**
 * Created by PhpStorm.
 * User: veider
 * Date: 2/20/18
 * Time: 4:45 AM
 */

namespace bonP\planBundle\Repository;


class ImageRepository extends \Doctrine\ORM\EntityRepository
{

    public function findurl($url)
    {
        $q = $this->getEntityManager()->createQuery("SELECT i FROM planBundle:image i WHERE i.url=:url")->setParameter('url',$url);

        return $q->getResult();
    }
}