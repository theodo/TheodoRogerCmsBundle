<?php

/*
 * This file is part of the Thoth CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\ThothCmsBundle\Extensions\Twig\Extension;

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
            'page_url'  => new \Twig_Function_Method($this, 'getFullUrl'),
            'media_url' => new \Twig_Function_Method($this, 'getMediaUrl'),
        );
    }

    public function getFullUrl($slug)
    {
        $page = $this->em->getRepository('TheodoThothCmsBundle:Page')->findOneBySlug($slug);

        return $this->generator->generate('page', array('slug' => $page->getFullSlug()), true);
    }

    public function getMediaUrl($name)
    {
        $media = $this->em->getRepository('TheodoThothCmsBundle:Media')->findOneByName($name);
        if ($media)
        {
            return '/uploads/'.$media->getPath();
        }

        return '';
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
