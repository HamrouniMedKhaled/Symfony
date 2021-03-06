<?php

namespace bonP\enseigneBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="bonP\enseigneBundle\Repository\MenuRepository")
 */
class Menu
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
     * @ORM\Column(name="contenu", type="string", length=255)
     */
    private $contenu;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;


    /**
     * @ManyToOne(targetEntity="Enseigne", inversedBy="menus")
     * @JoinColumn(name="enseigne_id", referencedColumnName="id")
     */
    protected $enseigne;

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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Menu
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
     * Set prix
     *
     * @param float $prix
     *
     * @return Menu
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
     * Set user
     *
     * @param \bonP\enseigneBundle\Entity\Enseigne $user
     *
     * @return Menu
     */
    public function setUser(\bonP\enseigneBundle\Entity\Enseigne $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \bonP\enseigneBundle\Entity\Enseigne
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set enseigne
     *
     * @param \bonP\enseigneBundle\Entity\Enseigne $enseigne
     *
     * @return Menu
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
}
