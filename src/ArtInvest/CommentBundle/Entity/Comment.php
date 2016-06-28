<?php

namespace ArtInvest\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Comment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "10",
     *      minMessage = "Your comment must be at least {{ limit }} characters",
     * )
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var \DateTime
     * @Assert\Date()
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var boolean
     *  
     * @ORM\Column(name="reported", type="boolean", nullable=true)
     */
    private $reported;

    /**
     * @ORM\ManyToOne(targetEntity="ArtInvest\ProjectBundle\Entity\Project", inversedBy="comments")
     * @ORM\JoinColumn(name="projectId", referencedColumnName="id")
     */
    protected $project;

    /**
     * @ORM\ManyToOne(targetEntity="ArtInvest\UserBundle\Entity\User", inversedBy="comment")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    protected $user;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Comment
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set reported
     *
     * @param boolean $reported
     *
     * @return Comment
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
     * @param \ArtInvest\UserBundle\Entity\User $user
     *
     * @return Comment
     */
    public function setUser(\ArtInvest\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ArtInvest\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set project
     *
     * @param \ArtInvest\ProjectBundle\Entity\Project $project
     *
     * @return Comment
     */
    public function setProject(\ArtInvest\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \ArtInvest\ProjectBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

}
