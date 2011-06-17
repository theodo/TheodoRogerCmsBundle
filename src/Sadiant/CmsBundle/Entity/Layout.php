<?php

namespace Sadiant\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var Sadiant\CmsBundle\Entity\Page
     */
    private $pages;

    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add pages
     *
     * @param Sadiant\CmsBundle\Entity\Page $pages
     */
    public function addPages(\Sadiant\CmsBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;
    }

    /**
     * Get pages
     *
     * @return Doctrine\Common\Collections\Collection $pages
     */
    public function getPages()
    {
        return $this->pages;
    }
}