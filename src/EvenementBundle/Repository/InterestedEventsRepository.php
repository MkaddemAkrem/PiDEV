<?php

namespace EvenementBundle\Repository;
use UserBundle\Entity\User;
/**
 * InterestedEventsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InterestedEventsRepository extends \Doctrine\ORM\EntityRepository
{
    public function isInterested($event, User $user)
    {
        return
            $this->createQueryBuilder('c')
                ->andWhere('c.user = :user')
                ->andWhere('c.event = :fav')
                ->setParameter('fav', $event)
                ->setParameter('user', $user)
                ->getQuery()->getResult();

    }
    public function countInterestEvent($eventid,$userid){

        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT count(f)
        FROM EvenementBundle:InterestedEvents f
        WHERE f.user = :usr
        AND f.event = :ev'
        )->setParameter('usr', $userid)
            ->setParameter('ev',$eventid);

        return $query->getSingleScalarResult();
    }
}
