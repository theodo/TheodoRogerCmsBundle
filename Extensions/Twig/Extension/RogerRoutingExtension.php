<?php

/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\RogerCmsBundle\Extensions\Twig\Extension;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManager;
use Theodo\RogerCmsBundle\Entity\Media;

/**
 * Generate cms frontend urls
 *
 * @author Mathieu Dähne <mathieud@theodo.fr>
 */
class RogerRoutingExtension extends \Twig_Extension
{
    private $generator;

    /**
     * Set dependencies.
     *
     * @param UrlGeneratorIntreface $generator Generator used for creating urls
     * @param EntityManager         $em        Entity manager to provide pages
     */
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

    /**
     * Generates a full url from a cms page slug
     *
     * @param string $slug
     *
     * @return string Generated url
     */
    public function getFullUrl($slug)
    {
        $page = $this->em->getRepository('TheodoRogerCmsBundle:Page')
            ->findOneBySlug($slug);

        return $this->generator
            ->generate('theodo_roger_cms_page', array('slug' => $page->getFullSlug()), true);
    }

    /**
     * Generates url for a media file of given name
     *
     * @param string $name Name of media file
     *
     * @return string Empty if file does not exist
     */
    public function getMediaUrl($name)
    {
        $media = $this->em->getRepository('TheodoRogerCmsBundle:Media')
            ->findOneByName($name);

        if ($media) {
            return '/' . Media::getUploadRootDir() . '/' . $media->getPath();
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
        return 'theodo_roger_cms_routing';
    }
}
