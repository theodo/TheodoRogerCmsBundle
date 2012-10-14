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
    public static $types = array('page', 'snippet', 'layout');

    /**
     * @var ContentRepositoryInterface
     */
    protected $contentRepository = null;

    /**
     * @var Twig_LoaderInterface
     */
    protected $fallbackLoader = null;

    /**
     * Registers content repository and fallback loader
     *
     * @param ContentRepositoryInterface $contentRepository Cms objects repository
     * @param Twig_LoaderInterface       $fallbackLoader    Twig loader for objects not handled by CMS
     * @param String                     $fallbackPath      Path to use by Twig loader
     *
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since  2011-06-22
     */
    public function __construct(ContentRepositoryInterface $contentRepository, Twig_LoaderInterface $fallbackLoader = null, $fallbackPath = null)
    {
        $this->contentRepository = $contentRepository;
        $this->fallbackLoader = $fallbackLoader;
        if ($this->fallbackLoader instanceof Twig_Loader_Filesystem
            && $fallbackPath != null) {
          $pathPrefix = __DIR__.'/../../../../../';

          if (!file_exists($pathPrefix.$fallbackPath)) {
              throw new \InvalidArgumentException('The specified fallback path does not exist. Tried to access: '.$pathPrefix.$fallbackPath);
          }

          $this->fallbackLoader->addPath($pathPrefix.$fallbackPath);
        }
    }

    /**
     * Getter for content repository
     *
     * @return ContentRepositoryInterface The current content repository
     *
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-22
     */
    public function getContentRepository()
    {
        return $this->contentRepository;
    }

    /**
     * Setter for content repository
     *
     * @param ContentRepositoryInterface $contentRepository
     *
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-22
     */
    public function setContentRepository(ContentRepositoryInterface $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * Parse an identifier of an RogerCms object and return it's name and type
     *
     * @param string $name A CMS object identifier
     *
     * @return array|false Array of type and name, false if identifier contains no type
     */
    public static function parseName($name)
    {
        $nameParts = explode(':', $name);
        if (count($nameParts) < 2) {
            return false;
        }

        $type = array_shift($nameParts);

        if (!in_array($type, self::$types)) {
            return false;
        }
        $name = implode(':', $nameParts);

        return array($type, $name);
    }

    /**
     * Gets the source code of a template, given its name.
     * Name format can be 'name' or 'type:name'
     * where type is 'page', 'layout', or 'snippet'
     *
     * @param string $name The name of the template to load
     *
     * @return string The template source code
     *
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2011-06-22
     */
    public function getSource($name)
    {
        $parsedInfo = self::parseName($name);

        if ($parsedInfo === false) {
            return $this->fallbackLoader->getSource($name);
        } else {
            list($type, $name) = $parsedInfo;
        }

        // Load source from content repository
        $source = $this->getContentRepository()->getSourceByNameAndType($name, $type);

        // Check source
        if (!$source) {
            throw new Twig_Error_Loader('Template "'.$name.'" not found in the database for type: '.$type);
        }

        return $source;
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The cache key
     */
    public function getCacheKey($name)
    {
        if (self::parseName($name) === false) {
            return $this->fallbackLoader->getCacheKey($name);
        }

        return $name;
    }

    /**
     * Check if a non-cms template is still fresh.
     * Returns true for cms templates as their state is handled differently.
     *
     * @param string  $name The template name
     * @param integer $time Timestamp of the last modification time
     *
     * @return boolean
     */
    public function isFresh($name, $time)
    {
        if (self::parseName($name) === false) {
            return $this->fallbackLoader->isFresh($name, $time);
        }

        return true;
    }
}
