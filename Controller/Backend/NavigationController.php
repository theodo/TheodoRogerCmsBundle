<?php

/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\RogerCmsBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Exception\FileLoaderLoadException;


/**
 * This controller is used to override menu creation
 * by using a configurable file do define menu.
 */
class NavigationController extends Controller
{
    /**
     * Display navigation tabs
     *
     * @param Request $request
     *
     * @return Response
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-09-14
     */
    public function menuComponentAction($request)
    {
        // Build roger.menu.yml file path
        $path = $this->get('kernel')->getRootDir().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'roger.menu.yml';

        // Check file exists
        if (!file_exists($path)) {
            throw new FileLoaderLoadException(sprintf(
                "File \"%s\" can not be found!\nPlease create file from RogerCmsBundle/config/menu.yml",
                $path
            ));
        }

        // Load roger.menu.yml file
        $menu = Yaml::parse($path);

        return $this->render(
            'TheodoRogerCmsBundle:Navigation:menu-component.html.twig',
            array(
                'menu'    => $menu,
                'request' => $request
            )
        );
    }
}
