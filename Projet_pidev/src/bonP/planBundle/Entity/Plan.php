<?php

namespace bonP\planBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;


/**
 * Plan
 *
 * @ORM\Table(name="plan")
 * @ORM\Entity(repositoryClass="bonP\planBundle\Repository\PlanRepository")
 */
class Plan
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
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id" ,nullable=false)
     */
    private $genre;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score = 0;

    /**
     * @ManyToOne(targetEntity="bonP\userBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var integer
     *
     * @ORM\Column(name="reportnumber", type="integer")
     */
    private $reportnumber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="reported", type="boolean")
     */
    private $reported;

    /** @ORM\Column(name="date_ajout",type="datetime") */
    private $dateAjout;

    /**
     * @var bool
     *
     * @ORM\Column(name="lng", type="float")
     */
    private $lng;

    /**
     * @var bool
     *
     * @ORM\Column(name="lat", type="float")
     */
    private $lat;

    /**
     * @OneToMany(targetEntity="bonP\planBundle\Entity\Commentaire", mappedBy="plan" , cascade={"persist", "remove"})
     */
    private $commentaires;



    /**
     * @ORM\OneToOne(targetEntity="bonP\planBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $image;





    public function __construct() {
        $this->dateAjout = new \DateTime();
        $this->commentaires= new ArrayCollection();
        $this->reportnumber=0;
        $this->reported=false;

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
     * Set titre
     *
     * @param string $titre
     *
     * @return Plan
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
     * Set description
     *
     * @param string $description
     *
     * @return Plan
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
     * Set score
     *
     * @param integer $score
     *
     * @return Plan
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
     * Set active
     *
     * @param boolean $active
     *
     * @return Plan
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }



    /**
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     *
     * @return Plan
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * Get dateAjout
     *
     * @return \DateTime
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }



    /**
     * Set lon
     *
     * @param float $lon
     *
     * @return Plan
     */


    /**
     * Set lat
     *
     * @param float $lat
     *
     * @return Plan
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     *
     * @return Plan
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }





    /**
     * Set image
     *
     * @param \bonP\planBundle\Entity\Image $image
     *
     * @return Plan
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
     * Set genre
     *
     * @param \bonP\planBundle\Entity\Categorie $genre
     *
     * @return Plan
     */
    public function setGenre(\bonP\planBundle\Entity\Categorie $genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return \bonP\planBundle\Entity\Categorie
     */
    public function getGenre()
    {
        return $this->genre;
    }







    /**
     * Add commentaire
     *
     * @param \bonP\planBundle\Entity\Commentaire $commentaire
     *
     * @return Plan
     */
    public function addCommentaire(\bonP\planBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires[] = $commentaire;

        return $this;
    }

    /**
     * Remove commentaire
     *
     * @param \bonP\planBundle\Entity\Commentaire $commentaire
     */
    public function removeCommentaire(\bonP\planBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires->removeElement($commentaire);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }



    /**
     * Set reportnumber
     *
     * @param integer $reportnumber
     *
     * @return Plan
     */
    public function setReportnumber($reportnumber)
    {
        $this->reportnumber = $reportnumber;

        return $this;
    }

    /**
     * Get reportnumber
     *
     * @return integer
     */
    public function getReportnumber()
    {
        return $this->reportnumber;
    }

    /**
     * Set reported
     *
     * @param boolean $reported
     *
     * @return Plan
     */
    public function setReported($reported)
    {
        $this->reported = $reported;

        return $this;
    }

    /**
     * Get reported
     *
     * @return boolean
     */
    public function getReported()
    {
        return $this->reported;
    }







    /**
     * Set user
     *
     * @param \bonP\userBundle\Entity\User $user
     *
     * @return Plan
     */
    public function setUser(\bonP\userBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \bonP\userBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
