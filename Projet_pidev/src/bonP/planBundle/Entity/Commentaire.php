<?php

namespace bonP\planBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="bonP\planBundle\Repository\CommentaireRepository")
 */
class Commentaire
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
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;


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

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreation", type="datetime")
     */
    private $datecreation;


    /**
     * @ManyToOne(targetEntity="Plan", inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plan;

    /**
     * @ManyToOne(targetEntity="bonP\userBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    public function __construct()
    {
        $this->datecreation=new \DateTime();
        $this->reportnumber=0;
        $this->reported=false;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Commentaire
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set datecreation
     *
     * @param \DateTime $datecreation
     *
     * @return Commentaire
     */
    public function setDatecreation($datecreation)
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * Get datecreation
     *
     * @return \DateTime
     */
    public function getDatecreation()
    {
        return $this->datecreation;
    }

    /**
     * Set plan
     *
     * @param \bonP\planBundle\Entity\Plan $plan
     *
     * @return Commentaire
     */
    public function setPlan(\bonP\planBundle\Entity\Plan $plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return \bonP\planBundle\Entity\Plan
     */
    public function getPlan()
    {
        return $this->plan;
    }



    /**
     * Set user
     *
     * @param \bonP\userBundle\Entity\User $user
     *
     * @return Commentaire
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

    /**
     * Set reportnumber
     *
     * @param integer $reportnumber
     *
     * @return Commentaire
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
     * @return Commentaire
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
}
