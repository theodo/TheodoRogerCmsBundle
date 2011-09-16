<?php

/*
 * This file is part of the Thoth CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\ThothCmsBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Exception\FileLoaderLoadException;

use Theodo\ThothCmsBundle\Form\LayoutType;

class NavigationController extends Controller
{
    /**
     * Display navigation tabs
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-09-14
     */
    public function menuComponentAction()
    {
        // Build thoth.menu.yml file path
        $path = $this->get('kernel')->getRootDir().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'thoth.menu.yml';

        // Check file exists
        if (!file_exists($path)) {
            throw new FileLoaderLoadException(sprintf(
              "File \"%s\" can not be found!\nPlease create file from ThothCmsBundle/config/menu.yml",
              $path
            ));
        }

        // Load thoth.menu.yml file
        $menu = Yaml::parse($path);

        return $this->render(
            'TheodoThothCmsBundle:Navigation:menu-component.html.twig',
            array('menu' => $menu)
        );
    }
}
