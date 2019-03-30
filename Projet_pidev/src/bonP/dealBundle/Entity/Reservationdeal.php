<?php

namespace bonP\dealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Reservationdeal
 *
 * @ORM\Table(name="reservationdeal")
 * @ORM\Entity(repositoryClass="bonP\dealBundle\Repository\ReservationdealRepository")
 */
class Reservationdeal
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
     * @ManyToOne(targetEntity="Deal", inversedBy="reservations")
     * @JoinColumn(name="deal_id", referencedColumnName="id")
     */
    private $deal;

    /**
     * @ManyToOne(targetEntity="bonP\userBundle\Entity\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var bool
     *
     * @ORM\Column(name="payer", type="boolean")
     */
    private $payer = false;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr", type="integer")
     */
    private $nbr;


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
     * Set deal
     *
     * @param \bonP\dealBundle\Entity\Deal $deal
     *
     * @return Reservationdeal
     */
    public function setDeal(\bonP\dealBundle\Entity\Deal $deal = null)
    {
        $this->deal = $deal;

        return $this;
    }

    /**
     * Get deal
     *
     * @return \bonP\dealBundle\Entity\Deal
     */
    public function getDeal()
    {
        return $this->deal;
    }

    /**
     * Set user
     *
     * @param \bonP\userBundle\Entity\User $user
     *
     * @return Reservationdeal
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

    /**
     * Set payer
     *
     * @param boolean $payer
     *
     * @return Reservationdeal
     */
    public function setPayer($payer)
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Get payer
     *
     * @return boolean
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * Set nbr
     *
     * @param integer $nbr
     *
     * @return Reservationdeal
     */
    public function setNbr($nbr)
    {
        $this->nbr = $nbr;

        return $this;
    }

    /**
     * Get nbr
     *
     * @return integer
     */
    public function getNbr()
    {
        return $this->nbr;
    }
}
