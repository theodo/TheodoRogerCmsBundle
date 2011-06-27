<?php

namespace Sadiant\CmsBundle\Extensions;

use Twig_Environment;
use Twig_Function_Method;

class Twig_Cache_Database
{
    /**
     *
     * @var Twig_Environment 
     */
    protected $twig_environment = null;
    
    /**
     *
     * @param Twig_Environment $twig_environment 
     * @author fabriceb
     * @since 2001-06-24
     */
    public function __construct(Twig_Environment $twig_environment)
    {
        $this->twig_environment = $twig_environment;
    }
    
    /**
     *
     * @return Twig_Environment
     * @author fabriceb
     * @since 2001-06-24 
     */
    public function getTwigEnvironment()
    {
        return $this->twig_environment;
    }

    /**
     *
     * @param Twig_Environment $twig_environment 
     * @author fabriceb
     * @since 2001-06-24
     */
    public function setTwigEnvironment(Twig_Environment $twig_environment)
    {
        $this->twig_environment = $twig_environment;
    }
    
    /**
     *
     * @param string $name
     * @author fabriceb
     * @since 2001-06-24
     */
    public function invalidate($name)
    {
        @unlink($this->getTwigEnvironment()->getCacheFilename($name));
    }
    
    /**
     *
     * @param string $name
     * @author fabriceb
     * @since 2001-06-24
     */
    public function warmup($name/*, $ext*/)
    {
        /*if (!$this->getTwigEnvironment()->getFunction('url'))
        {
          $this->getTwigEnvironment()->addFunction('url', new \Twig_Function_Method($ext, 'getUrl'));
        }*/

        $this->getTwigEnvironment()->loadTemplate($name);
    }
}
