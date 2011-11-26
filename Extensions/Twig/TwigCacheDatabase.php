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
use Twig_Function_Method;

class TwigCacheDatabase
{
    /**
     *
     * @var Twig_Environment
     */
    protected $twig_environment = null;

    /**
     *
     * @param Twig_Environment $twig_environment
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-24
     */
    public function __construct(Twig_Environment $twig_environment)
    {
        $this->twig_environment = $twig_environment;
    }

    /**
     *
     * @return Twig_Environment
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-24
     */
    public function getTwigEnvironment()
    {
        return $this->twig_environment;
    }

    /**
     *
     * @param Twig_Environment $twig_environment
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-24
     */
    public function setTwigEnvironment(Twig_Environment $twig_environment)
    {
        $this->twig_environment = $twig_environment;
    }

    /**
     *
     * @param string $name
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-24
     */
    public function invalidate($name)
    {
        @unlink($this->getTwigEnvironment()->getCacheFilename($name));
    }

    /**
     *
     * @param string $name
     * @author Fabrice Bernhard <fabriceb@theodo.fr>
     * @since 2001-06-24
     */
    public function warmup($name/*, $ext*/)
    {
        $this->getTwigEnvironment()->loadTemplate($name);
    }
}
