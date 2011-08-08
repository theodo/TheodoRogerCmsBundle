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
use Theodo\ThothCmsBundle\Repository\PageRepository;
use Theodo\ThothCmsBundle\Form\PageType;
use Theodo\ThothCmsBundle\Entity\Page;

class PageController extends Controller
{

    /**
     * List pages
     *
     * @return Response
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function indexAction()
    {
        // Retrieve pages
        $pages = $this->get('thoth.content_repository')->getFirstTwoLevelPages();

        return $this->render('TheodoThothCmsBundle:Page:index.html.twig', array('pages' => $pages));
    }


    /**
     * Edit page action
     *
     * @param integer $id
     * @param integer $parent_id
     *
     * @return Response
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-21
     */
    public function editAction($id = null, $parent_id = null)
    {
        // new page
        if (!$id) {
            $page = new Page();
            $parent_page = $this->get('thoth.content_repository')->findOneById($parent_id);
            // Create the homepage
            if ($parent_page) {
                $page->setParentId($parent_page->getId());
                $page->setParent($parent_page);
            }
            else {
                $parent_page = 'homepage';
            }
        }
        // update page
        else {
            $page = $this->get('thoth.content_repository')->findOneById($id);
            $parent_page = $page->getParent();
        }
        
        // FABRICE

        // contenu -> découpage en blocks et layout

        $value = $page->getContent();
        $twig = $this->get('twig');
        $tokens = $twig->tokenize($value);
        $nodes = $twig->parse($tokens);
        $blocks = $nodes->getnode('blocks');
        foreach($blocks as $block)
        {
            $block_name = $block->getAttribute('name');
            $num_matches = preg_match('/{% block '.$block_name.' %}(.*){% endblock %}/s', $value, $matches);
            if ($num_matches > 0)
            {
                $block_content = $matches[1];
            }
            var_dump($block_name);
            var_dump($block_content);
        }
        var_dump($nodes->getnode('parent')->getAttribute('value'));
        die();

        // FIN FABRICE

        // Create form
        $form = $this->createForm(new PageType(), $page);

        // Retrieve request
        $request = $this->getRequest();

        // Initialize form hasErros
        $hasErrors = false;

        // Request is post
        if ($request->getMethod() == 'POST') {
            // Bind form
            $form->bindRequest($request);

            // Check form and save object
            if ($form->isValid())
            {
                // remove twig cached file
                $this->get('thoth.caching')->invalidate('page:'.$page->getName());

                $page = $form->getData();
                $this->get('thoth.content_repository')->save($page);

                $this->get('thoth.caching')->warmup('page:'.$page->getName());

                // Set redirect route
                $redirect = $this->redirect($this->generateUrl('page_list'));
                if ($request->get('save-and-edit'))
                {
                    $redirect = $this->redirect($this->generateUrl('page_edit', array('id' => $page->getId())));
                }

                return $redirect;
            }
            else
            {
                $hasErrors = true;
            }
        }

        return $this->render(
            'TheodoThothCmsBundle:Page:edit.html.twig',
            array(
                'form'        => $form->createView(),
                'page'        => $page,
                'hasErrors'   => $hasErrors,
                'parent_page' => $parent_page
            )
        );
    }

    /**
     * Remove page action
     *
     * @param integer $id
     * @return Response
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-21
     */
    public function removeAction($id)
    {
        // Retrieve request
        $request = $this->getRequest();

        // Retrieve page
        $page = $this->get('thoth.content_repository')->findOneById($id);

        // Request is post
        if ($request->getMethod() == 'POST') {
            // Delete page
            $this->get('thoth.content_repository')->remove($page);

            return $this->redirect($this->generateUrl('page_list'));
        }

        return $this->render(
            'TheodoThothCmsBundle:Page:remove.html.twig',
            array(
                'page' => $page
            )
        );
    }

    /**
     * Expand page action
     *
     * @param integer $id
     * @return response
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-23
     */
    public function expandAction($id)
    {
        // Retrieve request
        $request = $this->getRequest();

        // Retrieve page childrens
        $pages = $this->get('thoth.content_repository')->findOneById($id)->getChildren();

        return $this->render(
            'TheodoThothCmsBundle:Page:page-list.html.twig',
            array(
                'pages' => $pages,
                'level' => $request->get('level')
            )
        );
    }

    /**
     * Site map action
     *
     * @param integer $id
     * @return Response
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-23
     */
    public function siteMapComponentAction($from_id)
    {
        // Retrieve request
        $request = $this->getRequest();

        // Retrieve page
        $page = $this->get('thoth.content_repository')->findOneById($from_id);

        return $this->render(
            'TheodoThothCmsBundle:Page:site-map-component.html.twig',
            array(
                'page'  => $page,
                'level' => 0
            )
        );
    }
}
