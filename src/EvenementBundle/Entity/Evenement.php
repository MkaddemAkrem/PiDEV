<?php

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\EvenementRepository")
 */
class Evenement
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;


    /**
     * @var int
     *
     * @ORM\Column(name="Rating",type="integer",nullable=true)
     */
    private $Rating;



    /**
     * @var string
     *
     * @ORM\Column(name="localite", type="string", length=255)
     */
    private $localite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=255)
     */
    private $lat;
    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="string", length=255)
     */
    private $lng;
    /**
     * @ORM\Column(type="integer")
     */
    private $nbrGoing;
    /**
     * @ORM\Column(type="integer")
     */
    private $nbrInterested;

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;


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
     * Set titre
     *
     * @param string $titre
     *
     * @return Evenement
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }


    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set localite
     *
     * @param string $localite
     *
     * @return Evenement
     */
    public function setLocalite($localite)
    {
        $this->localite = $localite;

        return $this;
    }

    /**
     * Get localite
     *
     * @return string
     */
    public function getLocalite()
    {
        return $this->localite;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }



    /**
     * Set description
     *
     * @param string $description
     *
     * @return Evenement
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


/**
 * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User",inversedBy="evenements")
 * @ORM\JoinColumn(nullable=true)
 */
private $utilisateur;


    /**
     * Set utilisateur
     *
     * @param \UserBundle\Entity\User $utilisateur
     *
     * @return Evenement
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
     * Set lat
     *
     * @param string $lat
     *
     * @return Evenement
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     *
     * @return Evenement
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set nbrGoing
     *
     * @param integer $nbrGoing
     *
     * @return Evenement
     */
    public function setNbrGoing($nbrGoing)
    {
        $this->nbrGoing = $nbrGoing;

        return $this;
    }

    /**
     * Get nbrGoing
     *
     * @return integer
     */
    public function getNbrGoing()
    {
        return $this->nbrGoing;
    }

    /**
     * Set nbrInterested
     *
     * @param integer $nbrInterested
     *
     * @return Evenement
     */
    public function setNbrInterested($nbrInterested)
    {
        $this->nbrInterested = $nbrInterested;

        return $this;
    }

    /**
     * Get nbrInterested
     *
     * @return integer
     */
    public function getNbrInterested()
    {
        return $this->nbrInterested;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Evenement
     */
    public function setRating($rating)
    {
        $this->Rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->Rating;
    }
}
