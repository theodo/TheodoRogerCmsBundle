<?php

namespace Theodo\ThothCmsBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ContentEntityRepository implements ContentRepositoryInterface
{
    /**
     * @var EntityManager
     */
    protected $_em;

    /**
     * Initializes a new <tt>EntityRepository</tt>.
     *
     * @param EntityManager $em The EntityManager to use.
     */
    public function __construct($em)
    {
        $this->_em = $em;
    }

    /**
     * EntityManager getter
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->_em;
    }

    /**
     * Return source by name and type
     * 
     * @return string
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function getSourceByNameAndType($name, $type = 'page')
    {
        // Retrieve template
        $template = $this->getEntityManager()
                ->getRepository('TheodoThothCmsBundle:' . ucfirst($type))
                ->findOneByName($name);

        // Check template
        if (!$template)
        {
            return null;
        }

        return $template->getContent();
    }

    /**
     * Return page by slug
     * 
     * @return Page
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function getPageBySlug($slug)
    {
        // Retrieve page
        $page = $this->getEntityManager()
                ->getRepository('TheodoThothCmsBundle:Page')
                ->findOneBySlug($slug);

        return $page;
    }

    /**
     * Return home page
     * 
     * @return Page
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function getHomePage()
    {
        // Retrieve home page (page without parent_id)
        $page = $this->getEntityManager()
                ->getRepository('TheodoThothCmsBundle:Page')
                ->findOneBy(array('parent_id' => null));

        return $page;
    }

    /**
     * Return two first level pages
     * 
     * @return Page[]
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function getFirstTwoLevelPages()
    {
        return $this->getEntityManager()->getRepository('TheodoThothCmsBundle:Page')->queryForMainPages()->getResult();
    }

    /**
     * Remove object
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function remove($object = null)
    {
        // Check object
        if (!$object)
        {
            return null;
        }

        // Remove object
        $this->getEntityManager()->remove($object);
        $this->getEntityManager()->flush();
    }

    /**
     * Create object
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-29
     */
    public function create($object = null)
    {
        // Check object
        if (!$object)
        {
            return null;
        }

        // Create object
        $this->getEntityManager()->persist($object);
        $this->getEntityManager()->flush();
    }

    /**
     * Save object
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-05
     */
    public function getSnippetByName($name)
    {
        $snippet = $this->getEntityManager()
                ->getRepository('TheodoThothCmsBundle:Snippet')
                ->findOneByName($name);

        return $snippet;
    }

    /**
     * Save object
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-05
     */
    public function save($object = null)
    {
        // Check object
        if (!$object)
        {
            return null;
        }

        // save the object
        $this->getEntityManager()->persist($object);
        $this->getEntityManager()->flush();
    }

    /**
     * Retrieve object
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-05
     */
    public function findOneById($id, $type = 'page')
    {
        // Retrieve the object
        $object = $this->getEntityManager()
                ->getRepository('TheodoThothCmsBundle:' . ucfirst($type))
                ->findOneById($id);

        return $object;
    }

    /**
     * Retrieve all objects
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-05
     */
    public function findAll($type = 'page')
    {
        $objects = $this->getEntityManager()
                ->getRepository('TheodoThothCmsBundle:' . ucfirst($type))
                ->findAll();

        return $objects;
    }
}