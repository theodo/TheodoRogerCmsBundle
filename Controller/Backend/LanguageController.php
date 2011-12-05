<?php

/**
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\RogerCmsBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Theodo\RogerCmsBundle\Form\LanguageType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LanguageController extends Controller
{
    public function chooseAction()
    {
        $form = $this->get('form.factory')->create(
            new LanguageType(),
            array('language' => $this->get('session')->getLocale()),
            array('languages' => $this->container->getParameter('roger.admin.languages', null))
        );

        return $this->render('TheodoRogerCmsBundle:Language:choose.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function switchAction()
    {
        $form = $this->get('form.factory')->create(
            new LanguageType(),
            array('language' => $this->get('session')->getLocale()),
            array('languages' => $this->container->getParameter('roger.admin.languages', null))
        );

        $request = $this->get('request');
        $form->bindRequest($request);
        $referer = $request->headers->get('referer');

        if ($form->isValid()) {
            $locale = $form->get('language')->getData();
            $router = $this->get('router');

            // Create URL path to pass it to matcher
            $urlParts = parse_url($referer);
            $basePath = $request->getBaseUrl();
            $path = str_replace($basePath, '', $urlParts['path']);

            // Match route and get it's arguments
            $route = $router->match($path);
            $routeAttrs = array_replace($route, array('_locale' => $locale));
            $routeName = $routeAttrs['_route'];
            unset($routeAttrs['_route']);

            // Set Locale
            $this->get('session')->setLocale($locale);

            return new RedirectResponse($router->generate($routeName, $routeAttrs));
        }

        return new RedirectResponse($referer);
    }
}