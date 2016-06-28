<?php

namespace ArtInvest\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Media
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Media
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
     * @var array
     *
     * @Assert\File( maxSize="1M", mimeTypes={"image/jpg", "image/png", "image/gif"} )
     *
     * @ORM\Column(name="image", type="json_array", nullable = true)
     */
    private $image;

    /**
     * @var array
     *
     * @ORM\Column(name="video", type="json_array", nullable = true)
     */
    private $video;

    /**
     * @var array
     *
     * @Assert\File( maxSize="10M", mimeTypes={"application/pdf", "application/excel", "application/msword"} )
     *
     * @ORM\Column(name="document", type="string", length=255, nullable=true)
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity="ArtInvest\ProjectBundle\Entity\Project", inversedBy="medias")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $projects;

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
     * Set image
     *
     * @param array $image
     *
     * @return Media
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return array
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set video
     *
     * @param array $video
     *
     * @return Media
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return array
     */
    public function getVideo()
    {
        return $this->video;
    }



    /**
     * Get document
     *
     * @return UploadedFile
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Sets file.
     *
     * @param array $document
     * @return Media
     */
       public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Set projects
     *
     * @param \ArtInvest\ProjectBundle\Entity\Project $projects
     *
     * @return Media
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

    public function getFullDocumentPath() {
        return null === $this->document ? null : $this->getUploadRootDir(). $this->document;
    }
 
    protected function getUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return $this->getTmpUploadRootDir().$this->getId()."/";
    }
 
    protected function getTmpUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../web/document/';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadDocument() {
        // the file property can be empty if the field is not required
        if (null === $this->document) {
            return;
        }
        if (!is_string($this->document)) {
            
            if(!$this->id){
            $this->document->move($this->getTmpUploadRootDir(), $this->document->getClientOriginalName());
            }
            else{
                $this->document->move($this->getUploadRootDir(), $this->document->getClientOriginalName());
            }
            $this->setDocument($this->document->getClientOriginalName());    
        }
        
    }
 
    /**
     * @ORM\PostPersist()
     */
    public function moveDocument()
    {
        if (null === $this->document) {
            return;
        }
        if(!is_dir($this->getUploadRootDir())){
            mkdir($this->getUploadRootDir());
        }
        copy($this->getTmpUploadRootDir().$this->document, $this->getFullDocumentPath());
        unlink($this->getTmpUploadRootDir().$this->document);
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeDocument()
    {
        if (!empty($this->document)) {
            $files = glob($this->getUploadRootDir().'*');
            foreach ($files as $file) {
                unlink($file);
            }
        rmdir($this->getUploadRootDir());
        }
        
    }
}
