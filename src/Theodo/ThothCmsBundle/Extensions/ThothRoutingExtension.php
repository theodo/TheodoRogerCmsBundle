<?php

namespace Theodo\ThothCmsBundle\Extensions;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManager;

/**
 * Generate cms frontend urls
 *
 * @author Mathieu Dähne <mathieud@theodo.fr>
 */
class ThothRoutingExtension extends \Twig_Extension
{
    private $generator;

    public function __construct(UrlGeneratorInterface $generator, EntityManager $em)
    {
        $this->generator = $generator;
        $this->em = $em;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'thurl'  => new \Twig_Function_Method($this, 'getFullUrl'),
        );
    }

    public function getFullUrl($slug = '')
    {
        $page = $this->em->getRepository('TheodoThothCmsBundle:Page')->findOneBySlug($slug);

        return $this->generator->generate('page', array('slug' => $page->getFullSlug()), true);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'thoth_routing';
    }
}
