<?php

/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\RogerCmsBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Theodo\RogerCmsBundle\Repository\PageRepository;

use Theodo\RogerCmsBundle\Extensions\Twig_Loader_Database;
use Twig_Error_Syntax;
use Twig_Loader_Array;


class FrontendController extends Controller
{

    /**
     * Configures the caching settings of the response
     *
     * @param object $object
     * @param Response $response
     * @return Response
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-07
     */
    public static function configureCache($object, Response $response)
    {
        if ($object->getPublic()) {
            $response->setPublic();
            $response->setSharedMaxAge($object->getLifeTime());
        }
        if ($object->getCacheable()) {
            $response->setLastModified($object->getUpdatedAt());
        }
        $response->setMaxAge($object->getLifeTime());

        return $response;
    }


    /**
     * Displays a Roger page
     *
     * @param string $slug
     * @return Response
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-21
     */
    public function pageAction($slug, $variables = array())
    {
        // Get corresponding page
        if(!$slug) {
            $page = $this->get('roger.content_repository')->getHomepage();
        }
        else {
            $slug = explode('/', $slug);
            $slug = $slug[count($slug) - 1];
            $page = $this->get('roger.content_repository')->getPageBySlug($slug);
        }

        // Initialize new response
        $response = new Response();

        // Handle 404
        if (!$page || PageRepository::STATUS_PUBLISH !== $page->getStatus()) {
            $page = $this->get('roger.content_repository')->getPageBySlug('error404');
            if (!$page) {
                throw $this->createNotFoundException(sprintf('There is no page corresponding to slug "%s".', $slug));
            }
            $response->setStatusCode(404);
        }

        $response = self::configureCache($page, $response);

        if ($response->isNotModified($this->get('request'))) {
            // return the 304 Response immediately
            return $response;
        }

        $response->headers->set('Content-Type', $page->getContentType());

        return $this->get('roger.templating')->renderResponse('page:'.$page->getName(), array('page' => $page) + $variables, $response);
    }

    /**
     * Displays a Roger snippet to support ESI
     *
     * @param string $name
     * @param array $attributes
     * @return Response
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-21
     */
    public function snippetAction($name, $attributes = array())
    {

        $snippet = $this->get('roger.content_repository')->findOneByName($name, 'snippet');

        if (!$snippet) {
              throw $this->createNotFoundException('Snippet "'.$name.'" not found.');
        }

        $response = self::configureCache($snippet, new Response());

        if ($response->isNotModified($this->get('request'))) {
            // return the 304 Response immediately
            return $response;
        } else {
            return $this->get('roger.templating')->renderResponse(
                    'snippet:'.$snippet->getName(),
                    $attributes,
                    $response);
        }
    }
}
