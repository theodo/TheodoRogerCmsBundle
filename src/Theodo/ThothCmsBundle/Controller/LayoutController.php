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
use Theodo\ThothCmsBundle\Form\LayoutType;

class LayoutController extends Controller
{

    /**
     * Layout list
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     */
    public function indexAction()
    {
        $layouts = $this->get('thoth.content_repository')->findAll('layout');

        return $this->render('TheodoThothCmsBundle:Layout:index.html.twig',
                array('layouts' => $layouts)
                );
    }

    /**
     * Layout edit
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
            $layout = $this->get('thoth.content_repository')->findOneById($id, 'layout');
        }
        $form = $this->createForm(new LayoutType(), $layout);
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                // remove twig cached file
                if ($layout) {
                    $this->get('thoth.caching')->invalidate('layout:'.$layout->getName());
                }

                // save layout
                $layout = $form->getData();
                $this->get('thoth.content_repository')->save($layout);

                $this->get('thoth.caching')->warmup('layout:'.$layout->getName());

                // Set redirect route
                $redirect = $this->redirect($this->generateUrl('layout_list'));
                if ($request->get('save-and-edit')) {
                    $redirect = $this->redirect($this->generateUrl('layout_edit', array('id' => $layout->getId())));
                }

                return $redirect;
            }
        }

        return $this->render('TheodoThothCmsBundle:Layout:edit.html.twig',
                array(
                    'layout' => $layout,
                    'form' => $form->createView(),
                  )
                );
    }

    /**
     * Supprime un layout
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-21
     * @param integer $id
     */
    public function removeAction($id)
    {
        $layout = $this->get('thoth.content_repository')->findOneById($id, 'layout');

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $this->get('thoth.content_repository')->remove($layout);

            return $this->redirect($this->generateUrl('layout_list'));
        }

        return $this->render('TheodoThothCmsBundle:Layout:remove.html.twig',
                array(
                  'layout' => $layout
                ));
    }
}
