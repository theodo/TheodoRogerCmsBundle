<?php

/*
 * This file is part of the Thoth CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\ThothCmsBundle\Extensions\Twig;

use Twig_LoaderInterface;
use Twig_Error_Loader;
use Theodo\ThothCmsBundle\Repository\ContentRepositoryInterface;

/**
 * Loads a template from a repository.
 *
 */
class Twig_Loader_Repository implements Twig_LoaderInterface
{
    /**
     *
     * @var ContentRepositoryInterface
     */
    protected $content_repository = null;
    
    /**
     *
     * @param ContentRepositoryInterface $content_repository
     * @author fabriceb
     * @since 2001-06-22
     */
    public function __construct(ContentRepositoryInterface $content_repository)
    {
        $this->content_repository = $content_repository;
    }
    
    /**
     *
     * @return EntityManager
     * @author fabriceb
     * @since 2001-06-22 
     */
    public function getContentRepository()
    {
        return $this->content_repository;
    }

    /**
     *
     * @param ContentRepositoryInterface $content_repository
     * @author fabriceb
     * @since 2001-06-22
     */
    public function setContentRepository(ContentRepositoryInterface $content_repository)
    {
        $this->content_repository = $content_repository;
    }
    
    /**
     * Gets the source code of a template, given its name.
     * Name format can be 'name' or 'type:name' where type is 'page', 'layout', or 'snippet'
     *
     * @param  string $name The name of the template to load
     *
     * @return string The template source code
     * 
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2011-06-22
     */
    public function getSource($name)
    {
        $types = array('page', 'snippet', 'layout');
        
        // parsing names of the kind 'type:name' 
        $name_parts = explode(':', $name);
        if (count($name_parts) < 2)
        {
            throw new Twig_Error_Loader('A template type must be specified using the "type:name" syntax');
        }

        $name = $name_parts[1];
        $type = $name_parts[0];
        if (!in_array($type, $types))
        {
            throw new Twig_Error_Loader('Type "'.$type.'" is not accepted. Accepted types are: '.implode(', ', $types));
        }

        // Load source from content repository
        $source = $this->getContentRepository()->getSourceByNameAndType($name, $type);

        // Check source
        if (!$source)
        {
            throw new Twig_Error_Loader('Template "'.$name.'" not found in the database for type(s): '.implode(', ', $types));
        }

        return $source;
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param  string $name The name of the template to load
     *
     * @return string The cache key
     */
    public function getCacheKey($name)
    {
        return $name;
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     */
    public function isFresh($name, $time)
    {
        return true; // isFresh is handled by cache invalidation
    }
}
