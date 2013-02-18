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

use Theodo\RogerCmsBundle\Form\LayoutType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Controller for backend layout section
 *
 * @author Mathieu Dähne <mathieud@theodo.fr>
 * @author Cyrille Jouineau <cyrillej@theodo.fr>
 * @author Marek Kalnik <marekk@theodo.fr>
 * @author Fabrice Bernhard <fabriceb@theodo.fr>
 * @author Benjamin Grandfond <benjamin.grandfond@gmail.com>
 */
class LayoutController extends BackendController
{
    /**
     * Layouts list
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function indexAction()
    {
        if (false == $this->get('security.context')->isGranted('ROLE_ROGER_READ_DESIGN')) {
            throw new AccessDeniedException('You are not allowed to list layouts.');
        }

        $layouts = $this->get('theodo_roger_cms.content_repository')->findAll('layout');

        return $this->render('TheodoRogerCmsBundle:Layout:index.html.twig', array(
            'layouts' => $layouts
        ));
    }

    /**
     * Edit a layout
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     * @since 2011-06-29 cyrillej ($hasErrors, copied from PageController by vincentg)
     * @since 2011-07-06 mathieud ($hasErrors deleted)
     */
    public function editAction($id)
    {
        $layout = null;
        if ($id) {
            $layout = $this->getContentRepository()->findOneById($id, 'layout');
        }

        $form = $this->createForm(new LayoutType(), $layout);
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            if (false == $this->get('security.context')->isGranted('ROLE_ROGER_WRITE_DESIGN')) {
                throw new AccessDeniedException('You are not allowed to edit this layout.');
            }

            $form->bindRequest($request);

            if ($form->isValid()) {
                // remove twig cached file
                if ($layout) {
                    $this->get('theodo_roger_cms.caching')->invalidate('layout:'.$layout->getName());
                }

                // save layout
                $layout = $form->getData();
                $this->getContentRepository()->save($layout);

                $this->get('theodo_roger_cms.caching')->warmup('layout:'.$layout->getName());

                // Set redirect route
                $redirect = $this->redirect($this->generateUrl('roger_cms_layout_list'));
                if ($request->get('save-and-edit')) {
                    $redirect = $this->redirect($this->generateUrl('roger_cms_layout_edit', array('id' => $layout->getId())));
                }

                return $redirect;
            }
        }

        return $this->render('TheodoRogerCmsBundle:Layout:edit.html.twig', array(
            'layout' => $layout,
            'form' => $form->createView(),
        ));
    }

    /**
     * Layout remove
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-21
     */
    public function removeAction($id)
    {
        if (false == $this->get('security.context')->isGranted('ROLE_ROGER_DELETE_DESIGN')) {
            throw new AccessDeniedException('You are not allowed to delete this layout.');
        }

        $layout = $this->getContentRepository()->findOneById($id, 'layout');

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $this->getContentRepository()->remove($layout);

            return $this->redirect($this->generateUrl('roger_cms_layout_list'));
        }

        return $this->render('TheodoRogerCmsBundle:Layout:remove.html.twig', array(
            'layout' => $layout
        ));
    }
}
