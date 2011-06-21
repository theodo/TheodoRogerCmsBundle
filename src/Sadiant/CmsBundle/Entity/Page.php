<?php

namespace Sadiant\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sadiant\CmsBundle\Entity\Page
 */
class Page
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
     * @var string $slug
     */
    private $slug;

    /**
     * @var string $breadcrumb
     */
    private $breadcrumb;

    /**
     * @var string $description
     */
    private $description;

    /**
     * @var string $status
     */
    private $status;

    /**
     * @var integer $parent_id
     */
    private $parent_id;

    /**
     * @var integer $layout_id
     */
    private $layout_id;

    /**
     * @var Sadiant\CmsBundle\Entity\Page
     */
    private $children;

    /**
     * @var Sadiant\CmsBundle\Entity\Page
     */
    private $parent;

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set breadcrumb
     *
     * @param string $breadcrumb
     */
    public function setBreadcrumb($breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }

    /**
     * Get breadcrumb
     *
     * @return string $breadcrumb
     */
    public function getBreadcrumb()
    {
        return $this->breadcrumb;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set parent_id
     *
     * @param integer $parentId
     */
    public function setParentId($parentId)
    {
        $this->parent_id = $parentId;
    }

    /**
     * Get parent_id
     *
     * @return integer $parentId
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Set layout_id
     *
     * @param integer $layoutId
     */
    public function setLayoutId($layoutId)
    {
        $this->layout_id = $layoutId;
    }

    /**
     * Get layout_id
     *
     * @return integer $layoutId
     */
    public function getLayoutId()
    {
        return $this->layout_id;
    }

    /**
     * Add children
     *
     * @param Sadiant\CmsBundle\Entity\Page $children
     */
    public function addChildren(\Sadiant\CmsBundle\Entity\Page $children)
    {
        $this->children[] = $children;
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection $children
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param Sadiant\CmsBundle\Entity\Page $parent
     */
    public function setParent(\Sadiant\CmsBundle\Entity\Page $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Sadiant\CmsBundle\Entity\Page $parent
     */
    public function getParent()
    {
        return $this->parent;
    }
}