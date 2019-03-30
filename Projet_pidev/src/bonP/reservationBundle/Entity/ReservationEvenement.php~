<?php
/**
 * Created by PhpStorm.
 * User: yassine
 * Date: 22/02/2018
 * Time: 22:48
 */

namespace bonP\reservationBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;


/**
 * Reservation
 *
 * @ORM\Table(name="reservationevent")
 * @ORM\Entity(repositoryClass="bonP\reservationBundle\Repository\ReservationEventRepository")
 */
class ReservationEvenement
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
     * @ORM\Column(name="nombrplaces", type="integer")
     */
    private $nombrplaces;

    /**
     * @ManyToOne(targetEntity="bonP\enseigneBundle\Entity\Evenement")
     * @JoinColumn(name="evenement_id", referencedColumnName="id")
     */
    private $evenement;


    /**
     * @ManyToOne(targetEntity="bonP\userBundle\Entity\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;




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
     * Set nombrplaces
     *
     * @param integer $nombrplaces
     *
     * @return ReservationEvenement
     */
    public function setNombrplaces($nombrplaces)
    {
        $this->nombrplaces = $nombrplaces;

        return $this;
    }

    /**
     * Get nombrplaces
     *
     * @return integer
     */
    public function getNombrplaces()
    {
        return $this->nombrplaces;
    }

    /**
     * Set evenement
     *
     * @param \bonP\enseigneBundle\Entity\Evenement $evenement
     *
     * @return ReservationEvenement
     */
    public function setEvenement(\bonP\enseigneBundle\Entity\Evenement $evenement = null)
    {
        $this->evenement = $evenement;

        return $this;
    }

    /**
     * Get evenement
     *
     * @return \bonP\enseigneBundle\Entity\Evenement
     */
    public function getEvenement()
    {
        return $this->evenement;
    }



    /**
     * Set user
     *
     * @param \bonP\userBundle\Entity\User $user
     *
     * @return ReservationEvenement
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
