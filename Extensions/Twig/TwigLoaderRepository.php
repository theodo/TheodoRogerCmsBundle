<?php

/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\RogerCmsBundle\Extensions\Twig;

use Twig_LoaderInterface;
use Twig_Error_Loader;
use Twig_Loader_Filesystem;
use Theodo\RogerCmsBundle\Repository\ContentRepositoryInterface;

/**
 * Loads a template from a repository.
 *
 */
class TwigLoaderRepository implements Twig_LoaderInterface
{

    static $types = array('page', 'snippet', 'layout');
    /**
     *
     * @var ContentRepositoryInterface
     */
    protected $content_repository = null;

    /**
     *
     * @var Twig_LoaderInterface
     */
    protected $fallback_loader = null;

    /**
     *
     * @param ContentRepositoryInterface $content_repository
     * @param Twig_LoaderInterface $fallback_loader
     * @author fabriceb
     * @since 2001-06-22
     */
    public function __construct(ContentRepositoryInterface $content_repository, Twig_LoaderInterface $fallback_loader = null, $fallback_path = null)
    {
        $this->content_repository = $content_repository;
        $this->fallback_loader = $fallback_loader;
        if ($this->fallback_loader instanceof Twig_Loader_Filesystem && $fallback_path != null)
        {
          // fais le path relative au dossier principal du projet
          $path_prefix = __DIR__.'/../../../../../';

          if (!file_exists($path_prefix.$fallback_path))
          {
              throw new \InvalidArgumentException('The specified fallback path does not exist. Tried to access: '.$path_prefix.$fallback_path);
          }

          $this->fallback_loader->addPath($path_prefix.$fallback_path);
        }
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

    public static function parseName($name)
    {
        $name_parts = explode(':', $name);
        if (count($name_parts) < 2) {
            return false;
        }

        $type = $name_parts[0];
        if (!in_array($type, self::$types)) {
            return false;
        }
        $name = $name_parts[1];

        return array($type, $name);
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
        $parsed_info = self::parseName($name);

        if ($parsed_info === false) {
            return $this->fallback_loader->getSource($name);
        }
        else {
            list($type, $name) = $parsed_info;
        }

        // Load source from content repository
        $source = $this->getContentRepository()->getSourceByNameAndType($name, $type);

        // Check source
        if (!$source) {
            throw new Twig_Error_Loader('Template "'.$name.'" not found in the database for type: '.$type);
        }

        //$this->get('logger')->info('RogerCmsBundle: ('.$type.', '.$name.' loaded');

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
        if (self::parseName($name) === false) {
            return $this->fallback_loader->getCacheKey($name);
        }
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
        if (self::parseName($name) === false) {
            return $this->fallback_loader->isFresh($name, $time);
        }
        return true; // isFresh is handled by cache invalidation
    }
}
