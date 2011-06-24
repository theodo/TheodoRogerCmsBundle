<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sadiant\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    public function removeCache($name, $type)
    {
        // get twig environment
        $twig_environment = $this->get('twig');

        // Use DBLoader and Environment function ?
        // build file path @see TwigEnvironment
        $class = substr($twig_environment->getTemplateClassPrefix().md5($type.':'.$name), strlen($twig_environment->getTemplateClassPrefix()));
        $path = $twig_environment->getCache().'/'.substr($class, 0, 2).'/'.substr($class, 2, 2).'/'.substr($class, 4).'.php';

        // delete cached file from all environments
        // @TODO: smarter ?
        $envs = array('dev', 'prod', 'test');
        foreach ($envs as $env)
        {
            $path = preg_replace('/cache\/dev\/twig|cache\/prod\/twig|cache\/test\/twig/', 'cache/'.$env.'/twig', $path);
            // delete cached file
            if (file_exists($path))
            {
                unlink($path);
            }
        }
    }
}
