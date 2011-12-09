<?php

namespace Theodo\RogerCmsBundle\Repository;

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
        $template = $this->getRepository($type)->findOneByName($name);

        // Check template
        if (!$template) {
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
        $page = $this->getRepository('page')->findOneBySlug($slug);

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
        $page = $this->getRepository('page')->findOneBy(array('parent_id' => null));

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
        return $this->getRepository('page')->queryForMainPages()->getResult();
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
        if (!$object) {
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
        if (!$object) {
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
        $snippet = $this->getRepository('snippet')->findOneByName($name);

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
        if (!$object) {
            return null;
        }

        // save the object
        $this->getEntityManager()->persist($object);
        $this->getEntityManager()->flush();
    }

    /**
     * Retrieve object by name
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-07
     */
    public function findOneByName($name, $type)
    {
        // Retrieve the object
        $object = $this->getRepository($type)->findOneByName($name);

        return $object;
    }

    /**
     * Retrieve object by id
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-05
     */
    public function findOneById($id, $type = 'page')
    {
        // Retrieve the object
        $object = $this->getRepository($type)->findOneById($id);

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
        $objects = $this->getRepository($type)->findAll();

        return $objects;
    }

    /**
     *
     * @param string $type
     * @return EntityRepository
     *
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2011-07-07
     */
    public function getRepository($type)
    {
        return $this->getEntityManager()
                ->getRepository('TheodoRogerCmsBundle:' . ucfirst($type));
    }

    /**
     * Checks if layout of given name exists
     * Used by advanced mode, when template is specified manually
     *
     * @author Marek Kalnik <marekk@theodo.fr>
     * @since  2011-11-08
     * @param  String $name
     * @return Boolean If the layout of given name exists in database
     */
    public function layoutExists($name)
    {
        if ($name == '') {

            return false;
        }

        $layout_exists = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('count(l.id)')
            ->from('Theodo\RogerCmsBundle\Entity\Layout', 'l')
            ->where('l.name = :name')
            ->setParameter('name', $name)
            ->getQuery()->getSingleScalarResult();

        return (bool) $layout_exists;
    }
}