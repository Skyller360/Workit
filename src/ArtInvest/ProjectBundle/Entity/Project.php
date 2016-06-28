<?php

namespace ArtInvest\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Constraints\Constraints;
use Symfony\Component\Validator\Constraints as Assert;
//use Doctrine\Common\Collections\ArrayCollection;

// 
/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks  
 *
 **/
class Project
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
     * @Assert\NotBlank(message = "Title")
     *
     * @Assert\Length(
     *      min = "6",
     *      max = "23",
     *      minMessage = "Project title must be at least {{ limit }} characters",
     *      maxMessage = "Project title must be less than {{ limit }} characters"
     * )
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="delay", type="date")
     */
    private $delay;

    /**
     * @var integer
     *
     * @Assert\NotBlank(message = "Amount")
     *
     * @Assert\Range(
     *      min = "1",
     *      max = "9999999",
     *      minMessage = "Your objective must be more than {{ limit }} $",
     *      maxMessage = "Your objective must be less than {{ limit }} $"
     * )
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Abstract")
     *
     * @Assert\Length(
     *      min = "10",
     *      max = "140",
     *      minMessage = "Your project abstract must be at least {{limit}} characters",
     *      maxMessage = "Your project abstract must be less than {{limit}} characters;"
     * )
     * @ORM\Column(name="abstract", type="string", length=255)
     *
     */
    private $abstract;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Description")
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "risk")
     * @ORM\Column(name="risk", type="text")
     */
    private $risk;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="cover", type="string", length=255)
     *
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 1024,
     *     minHeight = 200,
     *     maxHeight = 1024
     * )      
     *
     */
    private $cover;

    /**
     * @var array
     *
     * @ORM\Column(name="goodies", type="string")
     */
    private $goodies;

    /**
     * @var string
     * @Assert\Url()
     *
     * @ORM\Column(name="video", type="string", length=255)
     *     
     *
     */
    private $video;

    /**
     * @var boolean
     *
     * @ORM\Column(name="accepted", type="boolean", nullable=true)
     */
    private $accepted;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=true)
     */
    private $deleted;

    /**
     * @var boolean
     *
     * @ORM\Column(name="reported", type="boolean", nullable=true)
     */
    private $reported;

    /**
     * @ORM\ManyToOne(targetEntity="ArtInvest\CategoryBundle\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="ArtInvest\UserBundle\Entity\User", inversedBy="projects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     *
     * @ORM\OneToMany(targetEntity="ArtInvest\CommentBundle\Entity\Comment", mappedBy="project")
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="ArtInvest\NewsBundle\Entity\News", mappedBy="projects")
     */
    protected $news;

    /**
     *
     *
     *
     * @ORM\OneToMany(targetEntity="ArtInvest\DonationBundle\Entity\Donation", mappedBy="projects")
     */
    protected $donations;

    /**
     * @ORM\OneToMany(targetEntity="ArtInvest\MediaBundle\Entity\Media", mappedBy="projects")
     */
    protected $medias;

    /**
    * 
    * 
    **/     

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
     * Set title
     *
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set delay
     *
     * @param \DateTime $delay
     *
     * @return Project
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Get delay
     *
     * @return \DateTime
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Project
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
     * Set abstract
     *
     * @param string $abstract
     *
     * @return Project
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;

        return $this;
    }

    /**
     * Get abstract
     *
     * @return string
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Project
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
     * Set risk
     *
     * @param string $risk
     *
     * @return Project
     */
    public function setRisk($risk)
    {
        $this->risk = $risk;

        return $this;
    }

    /**
     * Get risk
     *
     * @return string
     */
    public function getRisk()
    {
        return $this->risk;
    }

    /**
     * Set cover
     *
     * @param string $cover
     *
     * @return Project
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set goodies
     *
     * @param array $goodies
     *
     * @return Project
     */
    public function setGoodies($goodies)
    {
        $this->goodies = $goodies;

        return $this;
    }

    /**
     * Get goodies
     *
     * @return array
     */
    public function getGoodies()
    {
        return $this->goodies;
    }

    /**
     * Set video
     *
     * @param string $video
     *
     * @return Project
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set accepted
     *
     * @param boolean $accepted
     *
     * @return Project
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;

        return $this;
    }

    /**
     * Get accepted
     *
     * @return boolean
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Project
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set reported
     *
     * @param boolean $reported
     *
     * @return Project
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
     * Set category
     *
     * @param \ArtInvest\ProjectBundle\Entity\Category $category
     *
     * @return Project
     */
    public function setCategory(\ArtInvest\CategoryBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \ArtInvest\ProjectBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set user
     *
     * @param \ArtInvest\UserBundle\Entity\User $user
     *
     * @return Project
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
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    

    /**
     * Add news
     *
     * @param \ArtInvest\NewsBundle\Entity\News $news
     *
     * @return Project
     */
    public function addNews(\ArtInvest\NewsBundle\Entity\News $news)
    {
        $this->news[] = $news;

        return $this;
    }

    /**
     * Remove news
     *
     * @param \ArtInvest\NewsBundle\Entity\News $news
     */
    public function removeNews(\ArtInvest\NewsBundle\Entity\News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Add donation
     *
     * @param \ArtInvest\DonationBundle\Entity\Donation $donation
     *
     * @return Project
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
     * Add media
     *
     * @param \ArtInvest\MediaBundle\Entity\Media $media
     *
     * @return Project
     */
    public function addMedia(\ArtInvest\MediaBundle\Entity\Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \ArtInvest\MediaBundle\Entity\Media $media
     */
    public function removeMedia(\ArtInvest\MediaBundle\Entity\Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Add comment
     *
     * @param \ArtInvest\CommentBundle\Entity\Comment $comment
     *
     * @return Project
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
}
