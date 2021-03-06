<?php

namespace AnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="AnnonceBundle\Repository\commentaireRepository")
 */
class commentaire
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
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User",inversedBy="id")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="AnnonceBundle\Entity\Annonce",inversedBy="id")
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $annonce;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDatecom", type="datetime")
     */

    private $creationDatecom;

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
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set utilisateur
     *
     * @param \UserBundle\Entity\User $utilisateur
     *
     * @return commentaire
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
     * Set annonce
     *
     * @param \AnnonceBundle\Entity\Annonce $annonce
     *
     * @return commentaire
     */
    public function setAnnonce(\AnnonceBundle\Entity\Annonce $annonce = null)
    {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * Get annonce
     *
     * @return \AnnonceBundle\Entity\Annonce
     */
    public function getAnnonce()
    {
        return $this->annonce;
    }

    /**
     * Set creationDatecom
     *
     * @param \DateTime $creationDatecom
     *
     * @return commentaire
     */
    public function setCreationDatecom($creationDatecom)
    {
        $this->creationDatecom = $creationDatecom;

        return $this;
    }

    /**
     * Get creationDatecom
     *
     * @return \DateTime
     */
    public function getCreationDatecom()
    {
        return $this->creationDatecom;
    }
}
