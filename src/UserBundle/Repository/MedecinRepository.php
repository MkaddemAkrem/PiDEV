<?php

namespace UserBundle\Repository;

/**
 * MedecinRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MedecinRepository extends \Doctrine\ORM\EntityRepository
{
    public function rechercherDQL($rech)
    {
        $qd = $this->getEntityManager()
            ->createQuery("SELECT v FROM UserBundle:Medecin v WHERE v.nom like :nom ")
            ->setParameter('nom', '%'.$rech.'%');
        return $qd->getResult();
    }

    public function afficheMedecinValides()
    {

        $qd = $this->getEntityManager()
            ->createQuery("SELECT v FROM UserBundle:User v WHERE v.roles like :roles ")
            ->setParameter('roles', '%"' . 'ROLE_MEDECIN' . '"%');
        return $qd->getResult();

    }
    public function validerMedAction($id)
    {

        $qB = $this->getEntityManager()->createQueryBuilder();
        $qB ->update('UserBundle:User', 'p')
            ->set('p.enabled', '?1')
            ->where('p.id = ?2')
            ->setParameter(1, '1')
            ->setParameter(2, $id);

        return $qB->getQuery();

    }
    public function BloquerMedecinAction($id)
    {

        $qB = $this->getEntityManager()->createQueryBuilder();
        $qB ->update('UserBundle:User', 'p')
            ->set('p.enabled', '?1')
            ->where('p.id = ?2')
            ->setParameter(1, '0')
            ->setParameter(2, $id);

        return $qB->getQuery();

    }

}
