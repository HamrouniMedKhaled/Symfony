<?php

namespace bonP\badgeBundle\Entity;

use bonP\userBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * BadgeUnlock
 *
 * @ORM\Table(name="badge_unlock")
 * @ORM\Entity(repositoryClass="bonP\badgeBundle\Repository\BadgeUnlockRepository")
 */
class BadgeUnlock
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
     * @var Badge
     *
     * @ORM\ManyToOne(targetEntity="bonP\badgeBundle\Entity\Badge",inversedBy="unlocks")
     */
    private $badge;
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="bonP\userBundle\Entity\User")
     */
    private $user ;


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
     * Set badge
     *
     * @param \bonP\badgeBundle\Entity\Badge $badge
     *
     * @return BadgeUnlock
     */
    public function setBadge(\bonP\badgeBundle\Entity\Badge $badge = null)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Get badge
     *
     * @return \bonP\badgeBundle\Entity\Badge
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * Set user
     *
     * @param \bonP\userBundle\Entity\User $user
     *
     * @return BadgeUnlock
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
