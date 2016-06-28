<?php

namespace ArtInvest\UserBundle\Entity;

use Constraints\Constraints;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class User
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
     *
     * 
     *
     * @ORM\Column(name="fname", type="string", length=75, nullable=true)
     */
    private $fname;

    /**
     * @var string
     *
     * 
     *
     * @ORM\Column(name="lname", type="string", length=75, nullable=true)
     */
    private $lname;

    /**
     * @var string
     * @Assert\Length(
     *      min = "4",
     *      max = "50",
     *      minMessage = "Login must be at least {{ limit }} characters",
     *      maxMessage = "Login must be less than {{ limit }} characters"
     * )
     *
     * @Assert\Regex(
     *     pattern="/\w/",
     *     match=true,
     *     message="Your login can't contain special characters"
     * )
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="login", type="string", length=50)
     */
    private $login;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *      min = "6",
     *      max = "50",
     *      minMessage = "Password must be at least {{ limit }} characters",
     *      maxMessage = "Password must be less than {{ limit }} characters"
     * )
     *
     * @ORM\Column(name="hash", type="string", length=255)
     */
    private $hash;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(name="mail", type="string", length=100)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     maxSizeMessage = "The maxmimum allowed file size is 1024Ko.",
     *     mimeTypesMessage = "Only the filetypes image are allowed."
     * )
     */
    public $avatar;

    /**
     * @var string
     * @Assert\Url()
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     * @Assert\Length(
     *      min = 25,      
     *      minMessage = "Your biography must be at least {{ limit }} characters long",
     * )
     * @ORM\Column(name="bio", type="text", nullable=true)
     */
    private $bio;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=100, nullable=true)
     */
    private $location;

    /**
     * @var string
     * @Assert\Url()
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @var date
     * @Assert\Date()
     *
     * @ORM\Column(name="birthday", type="date")
     */
    private $birthday;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usergroup", type="boolean", nullable=true)
     */
    private $usergroup;

    /**
     * @ORM\OneToMany(targetEntity="ArtInvest\ProjectBundle\Entity\Project", mappedBy="user")
     */
    protected $projects;

    /**
     * @ORM\OneToMany(targetEntity="ArtInvest\CommentBundle\Entity\Comment", mappedBy="user")
     */
    protected $comment;

    /**
     * @ORM\OneToMany(targetEntity="ArtInvest\DonationBundle\Entity\Donation", mappedBy="user")
     */
    protected $donations;

    public function setId($id)
    {
        $this->id = $id;
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
     * Set fname
     *
     * @param string $fname
     *
     * @return User
     */
    public function setFname($fname)
    {
        $this->fname = $fname;

        return $this;
    }

    /**
     * Get fname
     *
     * @return string
     */
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * Set lname
     *
     * @param string $lname
     *
     * @return User
     */
    public function setLname($lname)
    {
        $this->lname = $lname;

        return $this;
    }

    /**
     * Get lname
     *
     * @return string
     */
    public function getLname()
    {
        return $this->lname;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return User
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return User
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set bio
     *
     * @param string $bio
     *
     * @return User
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return User
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return User
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set usergroup
     *
     * @param boolean $usergroup
     *
     * @return User
     */
    public function setUsergroup($usergroup)
    {
        $this->usergroup = $usergroup;

        return $this;
    }

    /**
     * Get usergroup
     *
     * @return boolean
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add project
     *
     * @param \ArtInvest\ProjectBundle\Entity\Project $project
     *
     * @return User
     */
    public function addProject(\ArtInvest\ProjectBundle\Entity\Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project
     *
     * @param \ArtInvest\ProjectBundle\Entity\Project $project
     */
    public function removeProject(\ArtInvest\ProjectBundle\Entity\Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add comment
     *
     * @param \ArtInvest\CommentBundle\Entity\Comment $comment
     *
     * @return User
     */
    public function addComment(\ArtInvest\CommentBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \ArtInvest\CommentBundle\Entity\Comment $comment
     */
    public function removeComment(\ArtInvest\CommentBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add donation
     *
     * @param \ArtInvest\DonationBundle\Entity\Donation $donation
     *
     * @return User
     */
    public function addDonation(\ArtInvest\DonationBundle\Entity\Donation $donation)
    {
        $this->donations[] = $donation;

        return $this;
    }

    /**
     * Remove donation
     *
     * @param \ArtInvest\DonationBundle\Entity\Donation $donation
     */
    public function removeDonation(\ArtInvest\DonationBundle\Entity\Donation $donation)
    {
        $this->donations->removeElement($donation);
    }

    /**
     * Get donations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDonations()
    {
        return $this->donations;
    }

    /**
     * Get comment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
}
