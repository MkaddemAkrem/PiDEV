<?php

namespace EvenementBundle\Repository;

/**
 * RatingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RatingRepository extends \Doctrine\ORM\EntityRepository
{

    public function AVGRating()
    {
        $query = $this->getEntityManager()
            ->createQuery("select c.evenement, AVG (c.rating) from 
         EvenementBundle:rating c GROUP BY c.evenement"
            );
        return $query->getResult();
    }
}






