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
use Theodo\ThothCmsBundle\Repository\PageRepository;
use Theodo\ThothCmsBundle\Form\PageType;
use Theodo\ThothCmsBundle\Entity\Page;

class PageController extends Controller
{
    /**
     * List pages
     *
     * @return string
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
     * New page action
     *
     * @return string
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-21
     */
    public function newAction($id)
    {
        // Retrieve request
        $request = $this->getRequest();

        // Retrieve parent page
        $parent_page = $this->get('thoth.content_repository')->findOneById($id);

        // Create new page
        $page = new Page();

        // Create the homepage
        if ($parent_page)
        {
            $page->setParentId($parent_page->getId());
            $page->setParent($parent_page);
        }
        else
        {
            $parent_page = 'homepage';
        }

        // Initialize form hasErros
        $hasErrors = false;

        // Create form
        $form = $this->createForm(new PageType(), $page);

        // Request is post, bind and save form
        if ($request->getMethod() == 'POST')
        {
            // Bind form
            $form->bindRequest($request);

            // Check form
            if ($form->isValid())
            {
                // Perform some action, such as save the object to the database
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
                'parent_page' => $parent_page,
                'hasErrors'   => $hasErrors,
                'isNew'       => true
            )
        );
    }

    /**
     * Edit page action
     *
     * @return string
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-21
     */
    public function editAction($id = null, $parent_id = null)
    {
        // Retrieve page
        // create page
        if ($id == null) {
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

        // Create form
        $form = $this->createForm(new PageType(), $page);

        // Retrieve request
        $request = $this->getRequest();

        // Initialize form hasErros
        $hasErrors = false;

        // Request is post
        if ($request->getMethod() == 'POST')
        {
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
     * @return string
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
        if ($request->getMethod() == 'POST')
        {
            // Delete page
            $this->get('thoth.content_repository')->remove($page);

            return $redirect = $this->redirect($this->generateUrl('page_list'));
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
     * @return string
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
