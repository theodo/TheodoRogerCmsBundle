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
use Theodo\RogerCmsBundle\Form\PageType;
use Theodo\RogerCmsBundle\Entity\Page;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Controller for backend page management
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @author Romain Barberi <romainb@theodo.fr>
 * @author Marek Kalnik <marekk@theodo.fr>
 * @author Fabrice Bernhard <fabriceb@theodo.fr>
 * @author Benjamin Grandfond <benjamin.grandfond@gmail.com>
 */
class PageController extends Controller
{
    /**
     * List pages
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function indexAction()
    {
        if (false == $this->get('security.context')->isGranted('ROLE_ROGER_READ_CONTENT')) {
            throw new AccessDeniedException('You are not allowed to list pages.');
        }

        // Retrieve pages
        $pages = $this->get('roger.content_repository')->getFirstTwoLevelPages();

        return $this->render('TheodoRogerCmsBundle:Page:index.html.twig', array('pages' => $pages));
    }

    /**
     * Edit page action
     *
     * @param  integer                                                                                       $id       Id of page to edit. Null for new page.
     * @param  integer                                                                                       $parentId Id of parent page in hierarchy. Null if page is homepage
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function editAction($id = null, $parentId = null)
    {
        // new page
        if (!$id) {
            $page = new Page();
            $parentPage = $this->get('roger.content_repository')->findOneById($parentId);
            // Create the homepage
            if ($parentPage) {
                $page->setParentId($parentPage->getId());
                $page->setParent($parentPage);
            } else {
                $parentPage = 'homepage';
            }
        }
        // update page
        else {
            $page = $this->get('roger.content_repository')->findOneById($id);
            $parentPage = $page->getParent();
        }

        // Get all layouts
        $layouts = $this->get('roger.content_repository')->findAll('layout');
        $pageContent = $page->getContent();

        // Get the current layout from the Twig content
        if (!($layoutName = $this->matchLayoutName($pageContent))) {
            $layoutName = null;
        }

        /**
         * Separate the different blocks from Twig
         * @TODO: this does not work with nested blocks.
         */
        if ($matches = $this->matchBlocks($pageContent)) {
            $tabs = array_combine($matches['block_name'], $matches['block_content']);
        } else {
            $tabs = array();
        }

        // Create form
        $form = $this->createForm(new PageType(), $page);

        // Retrieve request
        $request = $this->getRequest();

        // Initialize form hasErros
        $hasErrors = false;

        // Request is post
        if ($request->getMethod() == 'POST') {
            if (false == $this->get('security.context')->isGranted('ROLE_ROGER_WRITE_CONTENT')) {
                throw new AccessDeniedException('You are not allowed to edit this page.');
            }

            $this->bindEditForm($form, $request);

            // Check form and save object
            if ($form->isValid()) {
                // remove twig cached file
                $this->get('roger.caching')->invalidate('page:'.$page->getName());

                $page = $form->getData();

                if ($request->get('save-and-publish')) {
                    $page->publish();
                }

                $this->get('roger.content_repository')->save($page);

                $this->get('roger.caching')->warmup('page:'.$page->getName());

                if ($request->get('save-and-edit')) {
                    return $this->redirect($this->generateUrl('page_edit', array('id' => $page->getId())));
                }

                return $this->redirect($this->generateUrl('page_list'));
            } else {
                $hasErrors = true;
            }
        }

        return $this->render(
            'TheodoRogerCmsBundle:Page:edit.html.twig',
            array(
                'form'        => $form->createView(),
                'page'        => $page,
                'hasErrors'   => $hasErrors,
                'parent_page' => $parentPage,
                'layouts'     => $layouts,
                'layout_name' => $layoutName,
                'tabs'        => $tabs
            )
        );
    }

    /**
     * Remove page action
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function removeAction($id)
    {
        if (false == $this->get('security.context')->isGranted('ROLE_ROGER_DELETE_CONTENT')) {
            throw new AccessDeniedException('You are not allowed to delete this page.');
        }

        // Retrieve request
        $request = $this->getRequest();

        // Retrieve page
        $page = $this->get('roger.content_repository')->findOneById($id);

        // Request is post
        if ($request->getMethod() == 'POST') {
            // Delete page
            $this->get('roger.content_repository')->remove($page);

            return $this->redirect($this->generateUrl('page_list'));
        }

        return $this->render(
            'TheodoRogerCmsBundle:Page:remove.html.twig',
            array(
                'page' => $page
            )
        );
    }

    /**
     * Expand page action
     *
     * @param  integer                                    $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function expandAction($id)
    {
        if (false == $this->get('security.context')->isGranted('ROLE_ROGER_WRITE_CONTENT')) {
            throw new AccessDeniedException('You are not allowed to expand this page.');
        }

        // Retrieve request
        $request = $this->getRequest();

        // Retrieve page childrens
        $pages = $this->get('roger.content_repository')->findOneById($id)->getChildren();

        return $this->render(
            'TheodoRogerCmsBundle:Page:page-list.html.twig',
            array(
                'pages' => $pages,
                'level' => $request->get('level')
            )
        );
    }

    /**
     * Site map action
     *
     * @param  integer                                    $fromId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function siteMapComponentAction($fromId)
    {
        // Retrieve page
        $page = $this->get('roger.content_repository')->findOneById($fromId);

        return $this->render(
            'TheodoRogerCmsBundle:Page:site-map-component.html.twig',
            array(
                'page'  => $page,
                'level' => 0
            )
        );
    }

    /**
     * Bind the edit form
     *
     * @param Form    $form    The form to bind
     * @param Request $request Request to bind
     */
    protected function bindEditForm($form, $request)
    {
        $data = array_replace_recursive(
            $request->request->get($form->getName(), array()),
            $request->files->get($form->getName(), array())
        );

        /*
         * si la clef existe => on est en edition du twig brut
         * sinon on est uniquement sur l'edition des blocks
         */
        $pageContent = '';
        if (array_key_exists('content', $data)) {
            $pageContent = $data['content'];
        }

        // Gestion du layout
        $layoutName = $request->get('page_layout', '');

        // Gestion de la suppresion du layout
        $layoutReplace = ('' != $layoutName) ? "{% extends 'layout:".$layoutName."' %}" : "";

        // Maj du layout dans la page
        if (is_int(strpos($pageContent, "{% extends 'layout"))) {
            $pageContent = preg_replace("{% extends 'layout:(.*)' %}", $layoutReplace, $pageContent);
        } else {
            $pageContent = $layoutReplace.$pageContent;
        }

        // Gestion des blocks
        $blocks = $request->get('page_block', array());

        // Maj des différent blocks contenues dans la page
        foreach ($blocks as $blockName => $blockContent) {
            if (is_int(strpos($pageContent, '{% block '.$blockName.' %}'))) {
                $pageContent = preg_replace('{% block '.$blockName.' %}(.*){% endblock %}', '{% block '.$blockName.' %}'.$blockContent.'{% endblock %}', $pageContent);
            } else {
                $pageContent .= '{% block '.$blockName.' %}'.$blockContent.'{% endblock %}';
            }
        }

        $data['content'] = $pageContent;

        // Bind form
        $form->bind($data);
    }

    /**
     * @param String $pageContent Content to find layout name in.
     *
     * @return String|Boolean Layout name or false if no layout
     */
    private function matchLayoutName($pageContent)
    {
        $layoutRegexp = '#{% extends [\',\"]layout:(?P<layoutName>(.*))[\',\"] %}#sU';
        if (preg_match($layoutRegexp, $pageContent, $matches)) {
            return $matches['layoutName'];
        }

        return false;
    }

    /**
     * @param String $pageContent Content to search in.
     *
     * @return Array|Boolean Block name and content or false if no block
     */
    private function matchBlocks($pageContent)
    {
        if (!preg_match_all('#{% block (?P<block_name>(.*)) %}(?P<block_content>(.*)){% endblock %}#sU', $pageContent, $matches)) {

            return false;
        }

        return $matches;
    }
}
