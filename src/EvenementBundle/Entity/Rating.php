<?php

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * rating
 *
 * @ORM\Table(name="rating")
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\RatingRepository")
 */
class Rating
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
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var int
     *
     * @ORM\Column(name="evenement", type="integer")
     */
    private $evenement;


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
     * Set rating
     *
     * @param integer $rating
     *
     * @return rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set evenement
     *
     * @param integer $evenement
     *
     * @return rating
     */
    public function setEvenement($evenement)
    {
        $this->evenement = $evenement;

        return $this;
    }

    /**
     * Get evenement
     *
     * @return int
     */
    public function getEvenement()
    {
        return $this->evenement;
    }
}