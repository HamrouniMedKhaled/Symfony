<?php

namespace bonP\enseigneBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Enseigne
 *
 * @ORM\Table(name="enseigne")
 * @ORM\Entity(repositoryClass="bonP\enseigneBundle\Repository\EnseigneRepository")
 */
class Enseigne
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;


    /**
     * @var bool
     *
     * @ORM\Column(name="capacite", type="integer")
     */
    private $capacite;


    /**
     * @OneToOne(targetEntity="bonP\userBundle\Entity\Adresse" , cascade={"persist", "remove"})
     * @JoinColumn(name="adresse_id", referencedColumnName="id")
     */
    private $adresse;

    /**
     * @ORM\OneToOne(targetEntity="bonP\planBundle\Entity\Image",cascade={"persist","remove"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="bonP\planBundle\Entity\Categorie")
     * @ORM\JoinColumn(name="categorie_id",referencedColumnName="id", nullable=false)
     */
    private $categorie;


    /**
     * @ManyToOne(targetEntity="bonP\userBundle\Entity\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;



    /**
     * @OneToMany(targetEntity="Evenement", mappedBy="enseigne" , cascade={"persist", "remove"})
     */
    private $evenements;


    /**
     * @OneToMany(targetEntity="bonP\dealBundle\Entity\Deal", mappedBy="enseigne" , cascade={"persist", "remove"})
     */
    private $deals;


    /**
     * @OneToMany(targetEntity="bonP\reservationBundle\Entity\Reservation", mappedBy="enseigne" , cascade={"persist", "remove"})
     */
    private $reservations;


    /**
     * @OneToMany(targetEntity="Menu", mappedBy="enseigne" , cascade={"persist", "remove"})
     */
    protected $menus;

    public function __construct() {
        $this->evenements = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->deals = new ArrayCollection();
        $this->reservations=new ArrayCollection();
        $this->active=false;
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
     * @return Enseigne
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
     * Set active
     *
     * @param boolean $active
     *
     * @return Enseigne
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
     * Set image
     *
     * @param \bonP\planBundle\Entity\Image $image
     *
     * @return Enseigne
     */
    public function setImage(\bonP\planBundle\Entity\Image $image = null)
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
     * Set categorie
     *
     * @param \bonP\planBundle\Entity\Categorie $categorie
     *
     * @return Enseigne
     */
    public function setCategorie(\bonP\planBundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \bonP\planBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }





    /**
     * Set description
     *
     * @param string $description
     *
     * @return Enseigne
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
     * Add evenement
     *
     * @param \bonP\enseigneBundle\Entity\Evenement $evenement
     *
     * @return Enseigne
     */
    public function addEvenement(\bonP\enseigneBundle\Entity\Evenement $evenement)
    {
        $this->evenements[] = $evenement;

        return $this;
    }

    /**
     * Remove evenement
     *
     * @param \bonP\enseigneBundle\Entity\Evenement $evenement
     */
    public function removeEvenement(\bonP\enseigneBundle\Entity\Evenement $evenement)
    {
        $this->evenements->removeElement($evenement);
    }

    /**
     * Get evenements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvenements()
    {
        return $this->evenements;
    }





    /**
     * Add deal
     *
     * @param \bonP\dealBundle\Entity\Deal $deal
     *
     * @return Enseigne
     */
    public function addDeal(\bonP\dealBundle\Entity\Deal $deal)
    {
        $this->deals[] = $deal;

        return $this;
    }

    /**
     * Remove deal
     *
     * @param \bonP\dealBundle\Entity\Deal $deal
     */
    public function removeDeal(\bonP\dealBundle\Entity\Deal $deal)
    {
        $this->deals->removeElement($deal);
    }

    /**
     * Get deals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeals()
    {
        return $this->deals;
    }

    /**
     * Set capacite
     *
     * @param integer $capacite
     *
     * @return Enseigne
     */
    public function setCapacite($capacite)
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * Get capacite
     *
     * @return integer
     */
    public function getCapacite()
    {
        return $this->capacite;
    }

    /**
     * Set user
     *
     * @param \bonP\userBundle\Entity\User $user
     *
     * @return Enseigne
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
     * Add menu
     *
     * @param \bonP\enseigneBundle\Entity\Menu $menu
     *
     * @return Enseigne
     */
    public function addMenu(\bonP\enseigneBundle\Entity\Menu $menu)
    {
        $this->menus[] = $menu;

        return $this;
    }

    /**
     * Remove menu
     *
     * @param \bonP\enseigneBundle\Entity\Menu $menu
     */
    public function removeMenu(\bonP\enseigneBundle\Entity\Menu $menu)
    {
        $this->menus->removeElement($menu);
    }

    /**
     * Get menus
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMenus()
    {
        return $this->menus;
    }



    /**
     * Set adresse
     *
     * @param \bonP\userBundle\Entity\Adresse $adresse
     *
     * @return Enseigne
     */
    public function setAdresse(\bonP\userBundle\Entity\Adresse $adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return \bonP\userBundle\Entity\Adresse
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Add reservation
     *
     * @param \bonP\reservationBundle\Entity\Reservation $reservation
     *
     * @return Enseigne
     */
    public function addReservation(\bonP\reservationBundle\Entity\Reservation $reservation)
    {
        $this->reservations[] = $reservation;

        return $this;
    }

    /**
     * Remove reservation
     *
     * @param \bonP\reservationBundle\Entity\Reservation $reservation
     */
    public function removeReservation(\bonP\reservationBundle\Entity\Reservation $reservation)
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
