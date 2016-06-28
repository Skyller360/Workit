<?php

namespace ArtInvest\DonationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Donation
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Donation
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
     * @var boolean
     *
     * @ORM\Column(name="hidden", type="boolean")
     */
    private $hidden;

    /**
     * @var integer
     * @Assert\Range(
     *      min = 1,
     *      max = 9999999,
     *      minMessage = "Your donation must be at least {{ limit }}",
     *      maxMessage = "Your donation cannot be higher than {{ limit }}"
     * )
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="ArtInvest\ProjectBundle\Entity\Project", inversedBy="donations")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $projects;

    /**
     * @ORM\ManyToOne(targetEntity="ArtInvest\UserBundle\Entity\User", inversedBy="donations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
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
     * Set hidden
     *
     * @param boolean $hidden
     *
     * @return Donation
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Donation
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }


    /**
     * Set projects
     *
     * @param \ArtInvest\ProjectBundle\Entity\Project $projects
     *
     * @return Donation
     */
    public function setProjects(\ArtInvest\ProjectBundle\Entity\Project $projects = null)
    {
        $this->projects = $projects;

        return $this;
    }

    /**
     * Get projects
     *
     * @return \ArtInvest\ProjectBundle\Entity\Project
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Set user
     *
     * @param \ArtInvest\UserBundle\Entity\User $user
     *
     * @return Donation
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
}
