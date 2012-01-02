<?php

namespace Theodo\RogerCmsBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\File;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Media
{    /**
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
    protected $file;

    /**
     * @param String $oldPath
     */
    protected $oldPath;

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
     * @var datetime $created_at
     */
    private $created_at;

    /**
     * @var datetime $updated_at
     */
    private $updated_at;


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
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Media validator
     *
     *
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        // Name validator: not null
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addConstraint(new UniqueEntity(array('fields' => array('name'))));

        // file validator: not null
        $metadata->addPropertyConstraint('file', new File());
    }

    /**
     * @ORM\prePersist
     * @ORM\preUpdate
     */
    public function preUpload()
    {
        if ($this->file) {
            $this->setPath(md5($this->name).'.'.$this->file->guessExtension());
        }
    }

    /**
     * @ORM\postPersist
     * @ORM\postUpdate
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

        // If the object has been updated, we remove the old file
        if ($this->oldPath) {
            $path = self::getUploadRootDir().'/'.$this->oldPath;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    /**
     * @ORM\postRemove
     */
    public function removeUpload()
    {
        if ($file = $this->getFullPath()) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    public function getFullPath()
    {
        return self::getUploadRootDir().'/'.$this->path;
    }

    public static function getUploadRootDir()
    {
      return 'uploads';
    }

    public function setFile($file)
    {
        $this->file = $file;
        /**
         * Change Doctrine mapped parameter to force livecycleEvents
         */
        $this->oldPath = $this->getPath();
        $this->setPath(null);
    }

    public function getFile()
    {

        return $this->file;
    }

    public function getExtension()
    {
        $path = $this->getPath();

        return $path ? substr($path, -3, 3) : null;
    }
}