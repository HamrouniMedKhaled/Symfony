<?php

namespace bonP\reservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;


/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="bonP\reservationBundle\Repository\ReservationRepository")
 */
class Reservation
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
     * @ORM\Column(name="nbplaces", type="integer")
     */
    private $nbplaces;

    /**
     * @var \Date
     *
     * @ORM\Column(name="datereservation", type="date")
     */
    private $datereservation;


    /**
     * @ManyToOne(targetEntity="bonP\enseigneBundle\Entity\Enseigne", inversedBy="reservations")
     * @JoinColumn(name="enseigne_id", referencedColumnName="id")
     */
    private $enseigne;


    /**
     * @ManyToOne(targetEntity="bonP\userBundle\Entity\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


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
     * Set nbplaces
     *
     * @param integer $nbplaces
     *
     * @return Reservation
     */
    public function setNbplaces($nbplaces)
    {
        $this->nbplaces = $nbplaces;

        return $this;
    }

    /**
     * Get nbplaces
     *
     * @return int
     */
    public function getNbplaces()
    {
        return $this->nbplaces;
    }

    /**
     * Set datereservation
     *
     * @param \DateTime $datereservation
     *
     * @return Reservation
     */
    public function setDatereservation($datereservation)
    {
        $this->datereservation = $datereservation;

        return $this;
    }

    /**
     * Get datereservation
     *
     * @return \DateTime
     */
    public function getDatereservation()
    {
        return $this->datereservation;
    }

    /**
     * Set enseigne
     *
     * @param \bonP\enseigneBundle\Entity\Enseigne $enseigne
     *
     * @return Reservation
     */
    public function setEnseigne(\bonP\enseigneBundle\Entity\Enseigne $enseigne = null)
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
     * Set user
     *
     * @param \bonP\userBundle\Entity\User $user
     *
     * @return Reservation
     */
    public function setUser(\bonP\userBundle\Entity\User $user = null)
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
