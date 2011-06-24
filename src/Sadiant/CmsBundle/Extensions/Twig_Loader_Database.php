<?php

namespace Sadiant\CmsBundle\Extensions;

use Twig_LoaderInterface;
use Twig_Error_Loader;
use Doctrine\ORM\EntityManager;
/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Loads a template from a string.
 *
 * When using this loader with a cache mechanism, you should know that a new cache
 * key is generated each time a template content "changes" (the cache key being the
 * source code of the template). If you don't want to see your cache grows out of
 * control, you need to take care of clearing the old cache file by yourself.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
class Twig_Loader_Database implements Twig_LoaderInterface
{
    /**
     *
     * @var EntityManager 
     */
    protected $entity_manager = null;
    
    /**
     *
     * @param EntityManager $entity_manager 
     * @author fabriceb
     * @since 2001-06-22
     */
    public function __construct(EntityManager $entity_manager)
    {
        $this->entity_manager = $entity_manager;
    }
    
    /**
     *
     * @return EntityManager
     * @author fabriceb
     * @since 2001-06-22 
     */
    public function getEntityManager()
    {
        return $this->entity_manager;
    }

    /**
     *
     * @param EntityManager $entity_manager 
     * @author fabriceb
     * @since 2001-06-22
     */
    public function setEntityManager(EntityManager $entity_manager)
    {
        $this->entity_manager = $entity_manager;
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
        
        $template = $this->getEntityManager()
                ->getRepository('SadiantCmsBundle:'.ucfirst($type))
                ->findOneByName($name);
        if (!$template)
        {
            throw new Twig_Error_Loader('Template "'.$name.'" not found in the database for type(s): '.implode(', ', $types));
        }
        
        return $template->getContent();
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
