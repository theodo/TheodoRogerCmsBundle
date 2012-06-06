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

use Twig_Environment;

/**
 * TwitCacheDatabase is a helper for managing twig cache of cms managed pages
 */
class TwigCacheDatabase
{
    /**
     * @var Twig_Environment
     */
    protected $twigEnvironment = null;

    /**
     * Sets twig environment
     *
     * @param Twig_Environment $twigEnvironment
     *
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-24
     */
    public function __construct(Twig_Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @return Twig_Environment
     *
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-24
     */
    public function getTwigEnvironment()
    {
        return $this->twigEnvironment;
    }

    /**
     * @param Twig_Environment $twigEnvironment
     *
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-24
     */
    public function setTwigEnvironment(Twig_Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * Invalidates cache for given object
     *
     * @param string $name
     *
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-24
     */
    public function invalidate($name)
    {
        @unlink($this->getTwigEnvironment()->getCacheFilename($name));
    }

    /**
     * Create Twig cache for given object
     *
     * @param string $name
     *
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-24
     */
    public function warmup($name)
    {
        $this->getTwigEnvironment()->loadTemplate($name);
    }
}
