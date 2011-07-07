<?php

/*
 * This file is part of the Thoth CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\ThothCmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Theodo\ThothCmsBundle\Repository\PageRepository;

use Theodo\ThothCmsBundle\Extensions\Twig_Loader_Database;
use Twig_Error_Syntax;
use Twig_Loader_Array;


class FrontendController extends Controller
{
    /**
     * Create and configure the response
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-07
     */
  public function configureCache($object, $response)
  {
        // Handle HTTP Cache
        $date = $object->getUpdatedAt();

        if ($object->getPublic()) {
            $response->setPublic();
            $response->setSharedMaxAge($object->getLifeTime());
        }
        if ($object->getCacheable()) {
            $response->setLastModified($date);
        }
        $response->setMaxAge($object->getLifeTime());

        return $response;
  }

    /**
     * Page display
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-21
     */
    public function pageAction($slug)
    {
        // Get corresponding page
        if(!$slug) {
            $page = $this->get('thoth.content_repository')->getHomepage();
        }
        else {
            $slug = explode('/', $slug);
            $slug = $slug[count($slug) - 1];
            $page = $this->get('thoth.content_repository')->getPageBySlug($slug);
        }

        // Initialize new response
        $response = new Response();

        // Handle 404
        if (!$page || PageRepository::STATUS_PUBLISH !== $page->getStatus()) {
            $page = $this->get('thoth.content_repository')->getPageBySlug('error404');
            if (!$page) {
                throw $this->createNotFoundException();
            }
            $response->setStatusCode(404);
        }

        $response = $this->configureCache($page, $response);

        if ($page->getCacheable() && $response->isNotModified($this->get('request'))) {
            // return the 304 Response immediately
            return $response;
        }

        $response->headers->set('Content-Type', $page->getContentType());

        return $this->get('thoth.templating')->renderResponse('page:'.$page->getName(), array('page' => $page), $response);
    }

    public function snippetAction($name, $attributes = array())
    {
        $snippet = $this->get('thoth.content_repository')->findOneByName($name, 'snippet');

        if (!$snippet)
        {
              throw $this->createNotFoundException();
        }

        $response = $this->configureCache($snippet, new Response());

        if ($response->isNotModified($this->get('request'))) {
            // return the 304 Response immediately
            return $response;
        } else {
            return $this->get('thoth.templating')->renderResponse('snippet:'.$snippet->getName(), array('snippet' => $snippet), $response);
        }
    }
}
