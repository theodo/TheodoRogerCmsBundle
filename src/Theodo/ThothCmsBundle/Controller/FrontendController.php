<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
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
     * Test for page display
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-21
     */
    public function pageAction($slug)
    {
        if(!$slug)
        {
            $page = $this->get('thoth.content_repository')->getHomepage();
        }
        else
        {
            $slug = explode('/', $slug);
            $slug = $slug[count($slug) - 1];
            $page = $this->get('thoth.content_repository')->getPageBySlug($slug);
        }

        if (!$page || PageRepository::STATUS_PUBLISH !== $page->getStatus())
        {
            if (!$page = $this->get('thoth.content_repository')->getPageBySlug('error404'))
            {
                throw $this->createNotFoundException();
            }
        }

        $date = $page->getUpdatedAt();
        // Initialize new response
        $response = new Response();
        // Set cache settings in one call
        // TODO user defined ?
        /*$response->setCache(array(
            'last_modified' => $date,
            'public'        => true,
        ));*/
        // esi test
        $response->setSharedMaxAge(60);

        if ($page->getCacheable() && $response->isNotModified($this->get('request'))) {
            // return the 304 Response immediately
            return $response;
        } else {
            // Set content type
            $response->headers->set('Content-Type', $page->getContentType());
            return $this->get('thoth.templating')->renderResponse('page:'.$page->getName(), array('page' => $page), $response);
        }
    }

    public function snippetAction($name)
    {
        $snippet = $this->get('thoth.content_repository')->getSnippetByName($name);

        if (!$snippet)
        {
              throw $this->createNotFoundException();
        }

        $date = $snippet->getUpdatedAt();
        // Initialize new response
        $response = new Response();
        // Set cache settings in one call
        // TODO user defined ?
        /*$response->setCache(array(
            'last_modified' => $date,
            'public'        => true,
        ));*/
        // esi test
        $response->setSharedMaxAge(30);

        if ($response->isNotModified($this->get('request'))) {
            // return the 304 Response immediately
            return $response;
        } else {
            return $this->get('thoth.templating')->renderResponse('snippet:'.$snippet->getName(), array('snippet' => $snippet), $response);
        }
    }
}
