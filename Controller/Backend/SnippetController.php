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
use Theodo\RogerCmsBundle\Form\SnippetType;

class SnippetController extends Controller
{

    /**
     * Snippet list
     *
     * @return Response
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     */
    public function indexAction()
    {
        $snippets = $this->get('roger.content_repository')->findAll('snippet');

        return $this->render('TheodoRogerCmsBundle:Snippet:index.html.twig',
                array('snippets' => $snippets,
                      'roger_admin_layout' => $this->container->getParameter('roger.admin.layout')
                     )
                );
    }

    /**
     * Snippet edit
     *
     * @param integer $id
     * @return Response
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     * @since 2011-06-29 cyrillej ($hasErrors, copied from PageController by vincentg)
     * @since 2011-07-06 mathieud ($hasErrors deleted)
     * @since 2011-07-08 cyrillej ($hasErrors readded^^)
     */
    public function editAction($id)
    {
        $snippet = null;
        if ($id) {
            $snippet = $this->get('roger.content_repository')->findOneById($id, 'snippet');
        }
        $form = $this->createForm(new SnippetType(), $snippet);
        $request = $this->get('request');

        // Initialize form hasErros
        $hasErrors = false;

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                // remove twig cached file
                if ($snippet) {
                    $this->get('roger.caching')->invalidate('snippet:'.$snippet->getName());
                }

                //save snippet
                $snippet = $form->getData();
                $this->get('roger.content_repository')->save($snippet);

                $this->get('roger.caching')->warmup('snippet:'.$snippet->getName());

                // Set redirect route
                $redirect = $this->redirect($this->generateUrl('snippet_list'));
                if ($request->get('save-and-edit')) {
                    $redirect = $this->redirect($this->generateUrl('snippet_edit', array('id' => $snippet->getId())));
                }

                return $redirect;
            }
            else {
                $hasErrors = true;
            }
        }

        return $this->render('TheodoRogerCmsBundle:Snippet:edit.html.twig',
                array(
                    'snippet' => $snippet,
                    'form' => $form->createView(),
                    'hasErrors'   => $hasErrors,
                    'roger_admin_layout' => $this->container->getParameter('roger.admin.layout')
                  )
                );
    }

    /**
     * Snippet delete
     *
     * @param integer $id
     * @return Response
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-21
     */
    public function removeAction($id)
    {
        $snippet = $snippet = $this->get('roger.content_repository')->findOneById($id, 'snippet');

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $snippet = $this->get('roger.content_repository')->remove($snippet);

            return $this->redirect($this->generateUrl('snippet_list'));
        }

        return $this->render('TheodoRogerCmsBundle:Snippet:remove.html.twig',
                array(
                  'snippet' => $snippet,
                  'roger_admin_layout' => $this->container->getParameter('roger.admin.layout')
                ));
    }
}
