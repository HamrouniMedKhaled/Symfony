<?php

namespace bonP\badgeBundle\Entity;

use bonP\dealBundle\Entity\Deal;
use bonP\userBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="bonP\badgeBundle\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="bonP\badgeBundle\Entity\Tag",cascade={"persist"})
     */
    private $tags;


    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="bonP\userBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @var Deal
     *
     * @ORM\ManyToOne(targetEntity="bonP\dealBundle\Entity\Deal")
     * @ORM\JoinColumn(name="deal_id", referencedColumnName="id")
     */
    private $deal;


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
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set user
     *
     * @param \bonP\userBundle\Entity\User $user
     *
     * @return Comment
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
     * Set deal
     *
     * @param \bonP\dealBundle\Entity\Deal $deal
     *
     * @return Comment
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
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tag
     *
     * @param \bonP\badgeBundle\Entity\Tag $tag
     *
     * @return Comment
     */
    public function addTag(\bonP\badgeBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \bonP\badgeBundle\Entity\Tag $tag
     */
    public function removeTag(\bonP\badgeBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
