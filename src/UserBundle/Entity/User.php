<?php
// src/AppBundle/Entity/User.php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $nom;

    /**
     *
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255,nullable=true)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="specialite", type="string", length=255,nullable=true)
     */
    private $specialite;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255,nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="string", length=255,nullable=true)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255,nullable=true)
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\RDV",mappedBy="utilisateur", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $rdv;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->rdv=new \Doctrine\Common\Collections\ArrayCollection();
        $this->adresses = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * @ORM\OneToMany(targetEntity="Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses",mappedBy="utilisateur", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $adresses;

    /**
     * Add rdv
     *
     * @param \UserBundle\Entity\RDV $rdv
     *
     * @return User
     */
    public function addRdv(\UserBundle\Entity\RDV $rdv)
    {
        $this->rdv[] = $rdv;

        return $this;
    }

    /**
     * Remove rdv
     *
     * @param \UserBundle\Entity\RDV $rdv
     */
    public function removeRdv(\UserBundle\Entity\RDV $rdv)
    {
        $this->rdv->removeElement($rdv);
    }

    /**
     * Get rdv
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRdv()
    {
        return $this->rdv;

    }
    /**
     * Get id
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public  function getId()
    {
       return $this->id;
    }

    /**
     * Set facebookID
     *
     * @param string $facebookID
     *
     * @return User
     */
    public function setFacebookID($facebookID)
    {
        $this->facebookID = $facebookID;

        return $this;
    }

    /**
     * Get facebookID
     *
     * @return string
     */
    public function getFacebookID()
    {
        return $this->facebookID;
    }

    /**
     * Set googleID
     *
     * @param string $googleID
     *
     * @return User
     */
    public function setGoogleID($googleID)
    {
        $this->googleID = $googleID;

        return $this;
    }

    /**
     * Get googleID
     *
     * @return string
     */
    public function getGoogleID()
    {
        return $this->googleID;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return User
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set specialite
     *
     * @param string $specialite
     *
     * @return User
     */
    public function setSpecialite($specialite)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return string
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return User
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
     * Set prix
     *
     * @param string $prix
     *
     * @return User
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return User
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Add adress
     *
     * @param \Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses $adress
     *
     * @return User
     */
    public function addAdress(\Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses $adress)
    {
        $this->adresses[] = $adress;

        return $this;
    }

    /**
     * Remove adress
     *
     * @param \Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses $adress
     */
    public function removeAdress(\Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses $adress)
    {
        $this->adresses->removeElement($adress);
    }

    /**
     * Get adresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdresses()
    {
        return $this->adresses;
    }
}
