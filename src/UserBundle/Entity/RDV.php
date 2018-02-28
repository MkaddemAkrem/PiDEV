<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * RDV
 *
 * @ORM\Table(name="r_d_v")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RDVRepository")
 */
class RDV
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\Column(name="date", type="string")
     *
     */
    private $date;

    /**
     *
     *
     * @ORM\Column(name="heure", type="string")
     *
     */
    private $heure;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User",inversedBy="id")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User",inversedBy="id")
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $medecin;

    /**
     * @ORM\Column(name="confirme", type="boolean")
     *
     */
    private $confirme;

    /**
     * @ORM\Column(name="annule", type="boolean")
     *
     */
    private $annule;
    /**
     * Get id
     *
     * @return int
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return RDV
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set heure
     *
     * @param \DateTime $heure
     *
     * @return RDV
     */
    public function setHeure($heure)
    {
        $this->heure = $heure;

        return $this;
    }

    /**
     * Get heure
     *
     * @return \DateTime
     */
    public function getHeure()
    {
        return $this->heure;
    }

    /**
     * Set utilisateur
     *
     * @param \UserBundle\Entity\User $utilisateur
     *
     * @return RDV
     */
    public function setUtilisateur(\UserBundle\Entity\User $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \UserBundle\Entity\User
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }





    /**
     * Set medecin
     *
     * @param \UserBundle\Entity\User $medecin
     *
     * @return RDV
     */
    public function setMedecin(\UserBundle\Entity\User $medecin = null)
    {
        $this->medecin = $medecin;

        return $this;
    }

    /**
     * Get medecin
     *
     * @return \UserBundle\Entity\User
     */
    public function getMedecin()
    {
        return $this->medecin;
    }

    /**
     * Set confirme
     *
     * @param boolean $confirme
     *
     * @return RDV
     */
    public function setConfirme($confirme)
    {
        $this->confirme = $confirme;

        return $this;
    }

    /**
     * Get confirme
     *
     * @return boolean
     */
    public function getConfirme()
    {
        return $this->confirme;
    }

    /**
     * Set annule
     *
     * @param boolean $annule
     *
     * @return RDV
     */
    public function setAnnule($annule)
    {
        $this->annule = $annule;

        return $this;
    }

    /**
     * Get annule
     *
     * @return boolean
     */
    public function getAnnule()
    {
        return $this->annule;
    }
}
