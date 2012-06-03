<?php

namespace Theodo\RogerCmsBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Theodo\RogerCmsBundle\Repository\PageRepository;
use Theodo\RogerCmsBundle\Validator\TwigSyntax;

/**
 * Theodo\RogerCmsBundle\Entity\Page
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
     * @var integer $parentId
     */
    private $parentId;

    /**
     * @var integer $layoutId
     */
    private $layoutId;

    /**
     * @var Theodo\RogerCmsBundle\Entity\Page
     */
    private $children;

    /**
     * @var Theodo\RogerCmsBundle\Entity\Page
     */
    private $parent;

    /**
     * @var DateTime $createdAt
     */
    private $createdAt;

    /**
     * @var DateTime $updatedAt
     */
    private $updatedAt;

    /**
     * @var date $publishedAt
     */
    private $publishedAt;

    /**
     * @var string $contentType
     */
    private $contentType;

    /**
     * @var boolean $cacheable
     */
    private $cacheable;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $keywords
     */
    private $keywords;

    /**
     * @var integer $lifetime
     */
    private $lifetime;

    /**
     * @var boolean $public
     */
    private $public;

    /**
     * Initalize children as ArrayCollection for new objects
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = PageRepository::STATUS_DRAFT;
        $this->created_at = new \DateTime();
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
     * Set parentId
     *
     * @param integer $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * Get parentId
     *
     * @return integer $parentId
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set layoutId
     *
     * @param integer $layoutId
     */
    public function setLayoutId($layoutId)
    {
        $this->layoutId = $layoutId;
    }

    /**
     * Get layoutId
     *
     * @return integer $layoutId
     */
    public function getLayoutId()
    {
        return $this->layoutId;
    }

    /**
     * Add children
     *
     * @param Theodo\RogerCmsBundle\Entity\Page $children
     */
    public function addChildren(\Theodo\RogerCmsBundle\Entity\Page $children)
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
     * @param Theodo\RogerCmsBundle\Entity\Page $parent
     */
    public function setParent(\Theodo\RogerCmsBundle\Entity\Page $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Theodo\RogerCmsBundle\Entity\Page $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set publishedAt
     *
     * @param date $publishedAt
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * Get publishedAt
     *
     * @return date $publishedAt
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Returns recursive slug
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-29
     *
     * @return String
     */
    public function getFullSlug()
    {
        $parent = $this->getParent();
        if ($parent && !$this->isHomepage()) {
            return $parent->getFullSlug().'/'.$this->getSlug();
        } else {
            return $this->getSlug();
        }
    }

    /**
     * Page validator
     *
     * @param ClassMetadata $metadata
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-22
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        // Name validator: not null
        $metadata->addPropertyConstraint('name', new NotBlank());

        // Slug validator: not null and unique
        $metadata->addPropertyConstraint('slug', new NotBlank());
        $metadata->addConstraint(new UniqueEntity(array('fields' => array('slug'))));

        // Content validator: not null
        $metadata->addPropertyConstraint('content', new NotBlank());
        $metadata->addPropertyConstraint('content', new TwigSyntax());

        // Status validator: not null and available
        $metadata->addPropertyConstraint('status', new NotBlank());
        $metadata->addPropertyConstraint('status', new Choice(array(
            'choices' => PageRepository::getAvailableStatus())
        ));
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set cacheable
     *
     * @param boolean $cacheable
     */
    public function setCacheable($cacheable)
    {
        $this->cacheable = $cacheable;
    }

    /**
     * Get cacheable
     *
     * @return boolean
     */
    public function getCacheable()
    {
        return $this->cacheable;
    }

    /**
     * Set lifetime
     *
     * @param integer $lifetime
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;
    }

    /**
     * Get lifetime
     *
     * @return integer
     */
    public function getLifetime()
    {
        return $this->lifetime;
    }

    /**
     * Set public
     *
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Retrieve the subtype part of the Content-type declaration
     *
     * @return string
     * @author cyrillej
     * @since 2011-07-05
     */
    public function getContentSubtype()
    {
        $subtypes = PageRepository::getAvailableContentSubtypes();

        return $subtypes[$this->contentType];
    }

    /**
     * Test if the page is a homepage
     *
     * @return Boolean
     *
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2011-08-19
     */
    public function isHomepage()
    {
        return $this->getSlug() == PageRepository::SLUG_HOMEPAGE;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * Set keywords
     *
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Add children
     *
     * @param Theodo\RogerCmsBundle\Entity\Page $children
     */
    public function addPage(\Theodo\RogerCmsBundle\Entity\Page $children)
    {
        $this->children[] = $children;
    }

    /**
     * Check if the page is published.
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->getStatus() == PageRepository::STATUS_PUBLISH;
    }

    /**
     * Set the status and the publication date
     * of the page.
     */
    public function publish()
    {
        $this->setStatus(PageRepository::STATUS_PUBLISH);
        $this->setPublishedAt(new \DateTime());
    }
}
