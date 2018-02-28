<?php

namespace AnnonceBundle\Repository;

/**
 * commentaireRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class commentaireRepository extends \Doctrine\ORM\EntityRepository
{
    public function findcomAction($ida){

        $qd=$this->getEntityManager()->createQuery("select  m from AnnonceBundle:commentaire m 
JOIN AnnonceBundle:Annonce v ON m.id_annonce=v.id where m.id_annonce like :ida ")
            ->setParameter('ida',$ida);
        return $qd->getResult();



    }
}
