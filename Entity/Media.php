<?php

namespace Theodo\RogerCmsBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\File;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for media files
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Media
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $path
     */
    private $path;

    /**
     * @var string $file
     */
    public $file;

    /**
     * Set Id
     *
     * @param integer $id
     */
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
     * Set path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var datetime $createdAt
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     */
    private $updatedAt;

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpload()
    {
        if ($this->file) {
            $this->setPath(md5($this->name).'.'.$this->file->guessExtension());
        }
    }

    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function upload()
    {
        if ($this->file == null) {
            return;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does automatically
        $this->file->move(self::getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove
     */
    public function removeUpload()
    {
        if ($file = $this->getFullPath()) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Returns physical path for the uploaded file
     *
     * @return string
     */
    public function getFullPath()
    {
        return self::getUploadRootDir().'/'.$this->path;
    }

    /**
     * Returns upload directory name
     *
     * @return string
     */
    public static function getUploadRootDir()
    {
      return 'uploads';
    }

    /**
     * Media validator
     *
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        // Name validator: not null
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addConstraint(new UniqueEntity(array('fields' => array('name'))));

        // file validator: not null
        $metadata->addPropertyConstraint('file', new File());
    }
}
