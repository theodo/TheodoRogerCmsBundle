<?php

namespace Sadiant\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Sadiant\CmsBundle\Entity\Layout
 */
class Layout
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var text $content
     */
    private $content;

    /**
     * @var string $content_type
     */
    private $content_type;


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
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

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
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content_type
     *
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->content_type = $contentType;
    }

    /**
     * Get content_type
     *
     * @return string $contentType
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * Layout validator
     *
     * 
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {        
        // Name validator: not null
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addConstraint(new UniqueEntity(array('fields' => array('name'))));

        // Content validator: not null
        $metadata->addPropertyConstraint('content', new NotBlank());
    }
}