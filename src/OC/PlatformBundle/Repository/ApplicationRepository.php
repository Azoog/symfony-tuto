<?php

namespace OC\PlatformBundle\Repository;

/**
 * ApplicationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ApplicationRepository extends \Doctrine\ORM\EntityRepository
{
    public function getApplicationsWithAdvert($limit)
    {
        $qb = $this
            ->createQueryBuilder('app')
            ->innerJoin('app.advert', 'a')
            ->addSelect('a')
            ->orderBy('app.date','DESC')
            ->setMaxResults($limit);
        
        return
            $qb->getQuery()
               ->getResult();
    }
}
