<?php

namespace Theodo\RogerCmsBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

use Theodo\RogerCmsBundle\Repository\PageRepository;
use Theodo\RogerCmsBundle\Validator\Unique;
use Theodo\RogerCmsBundle\Validator\Exists;
use Theodo\RogerCmsBundle\Validator\TwigSyntax;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

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
     * @var integer $parent_id
     */
    private $parent_id;

    /**
     * @var integer $layout_id
     */
    private $layout_id;

    /**
     * @var Theodo\RogerCmsBundle\Entity\Page
     */
    private $children;

    /**
     * @var Theodo\RogerCmsBundle\Entity\Page
     */
    private $parent;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

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
     * @var date $published_at
     */
    private $published_at;


    /**
     * Set published_at
     *
     * @param date $publishedAt
     */
    public function setPublishedAt($publishedAt)
    {
        $this->published_at = $publishedAt;
    }

    /**
     * Get published_at
     *
     * @return date $publishedAt
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     *
     * Returns recursive slug
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-29
     */
    public function getFullSlug()
    {
        $parent = $this->getParent();
        if ($parent && !$this->isHomepage()) {
            return $parent->getFullSlug().'/'.$this->getSlug();
        }
        else {
            return $this->getSlug();
        }
    }

    /**
     * Page validator
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-22
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        // Title validator: not null
        $metadata->addPropertyConstraint('title', new NotBlank());

        // Slug validator: not null and unique
        $metadata->addPropertyConstraint('slug', new NotBlank());
        $metadata->addConstraint(new UniqueEntity(array('fields' => array('slug'))));

        // Content validator: not null
        $metadata->addPropertyConstraint('content', new NotBlank());
        $metadata->addPropertyConstraint('content', new TwigSyntax());

        // Status validator: not null and available
        $metadata->addPropertyConstraint('status', new NotBlank());
        $metadata->addPropertyConstraint('status', new Choice(array('choices' => PageRepository::getAvailableStatus())));
    }
    /**
     * @var datetime $created_at
     */
    private $created_at;

    /**
     * @var datetime $updated_at
     */
    private $updated_at;


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
     * @var string $content_type
     */
    private $content_type;


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
     * @return string
     */
    public function getContentType()
    {
        return $this->content_type;
    }
    /**
     * @var boolean $cacheable
     */
    private $cacheable;


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
     * @var integer $lifetime
     */
    private $lifetime;

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
     * @var boolean $public
     */
    private $public;


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

        return $subtypes[$this->content_type];
    }

    /**
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2011-08-19
     */
    public function isHomepage()
    {
        return $this->getSlug() == PageRepository::SLUG_HOMEPAGE;
    }

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var text $keywords
     */
    private $keywords;

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
     * @param text $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Get keywords
     *
     * @return text
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
}
