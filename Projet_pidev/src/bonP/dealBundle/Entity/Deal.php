<?php

namespace bonP\dealBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Deal
 *
 * @ORM\Table(name="deal")
 * @ORM\Entity(repositoryClass="bonP\dealBundle\Repository\DealRepository")
 */
class Deal
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="tred", type="integer")
     */
    private $tred;

    /**
     * @var int
     *
     * @ORM\Column(name="visite", type="integer")
     */
    private $visite = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebut", type="date")
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datefin", type="date")
     */
    private $datefin;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;


    /**
     * @ORM\OneToOne(targetEntity="bonP\planBundle\Entity\Image", cascade={"persist","remove"})
     * @JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $image;


    /**
     * @ManyToOne(targetEntity="bonP\enseigneBundle\Entity\Enseigne", inversedBy="deals")
     * @JoinColumn(name="enseigne_id", referencedColumnName="id" , nullable=false)
     */
    private $enseigne;


    /**
     * @OneToMany(targetEntity="bonP\dealBundle\Entity\Reservationdeal", mappedBy="deal" , cascade={"persist", "remove"})
     */
    private $reservations;


    public function __construct() {
        $this->reservations = new ArrayCollection();

    }



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
     * Set nom
     *
     * @param string $nom
     *
     * @return Deal
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
     * Set score
     *
     * @param integer $score
     *
     * @return Deal
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return Deal
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set tred
     *
     * @param integer $tred
     *
     * @return Deal
     */
    public function setTred($tred)
    {
        $this->tred = $tred;

        return $this;
    }

    /**
     * Get tred
     *
     * @return int
     */
    public function getTred()
    {
        return $this->tred;
    }

    /**
     * Set visite
     *
     * @param integer $visite
     *
     * @return Deal
     */
    public function setVisite($visite)
    {
        $this->visite = $visite;

        return $this;
    }

    /**
     * Get visite
     *
     * @return int
     */
    public function getVisite()
    {
        return $this->visite;
    }

    /**
     * Set datedebut
     *
     * @param \DateTime $datedebut
     *
     * @return Deal
     */
    public function setDatedebut($datedebut)
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    /**
     * Get datedebut
     *
     * @return \DateTime
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     * Set datefin
     *
     * @param \DateTime $datefin
     *
     * @return Deal
     */
    public function setDatefin($datefin)
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * Get datefin
     *
     * @return \DateTime
     */
    public function getDatefin()
    {
        return $this->datefin;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Deal
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Deal
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
     * Set image
     *
     * @param \bonP\planBundle\Entity\Image $image
     *
     * @return Deal
     */
    public function setImage(\bonP\planBundle\Entity\Image $image )
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \bonP\planBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }





    /**
     * Set enseigne
     *
     * @param \bonP\enseigneBundle\Entity\Enseigne $enseigne
     *
     * @return Deal
     */
    public function setEnseigne(\bonP\enseigneBundle\Entity\Enseigne $enseigne )
    {
        $this->enseigne = $enseigne;

        return $this;
    }

    /**
     * Get enseigne
     *
     * @return \bonP\enseigneBundle\Entity\Enseigne
     */
    public function getEnseigne()
    {
        return $this->enseigne;
    }


    /**
     * Add reservation
     *
     * @param \bonP\dealBundle\Entity\Reservationdeal $reservation
     *
     * @return Deal
     */
    public function addReservation(\bonP\dealBundle\Entity\Reservationdeal $reservation)
    {
        $this->reservations[] = $reservation;

        return $this;
    }

    /**
     * Remove reservation
     *
     * @param \bonP\dealBundle\Entity\Reservationdeal $reservation
     */
    public function removeReservation(\bonP\dealBundle\Entity\Reservationdeal $reservation)
    {
        $this->reservations->removeElement($reservation);
    }

    /**
     * Get reservations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservations()
    {
        return $this->reservations;
    }
}
