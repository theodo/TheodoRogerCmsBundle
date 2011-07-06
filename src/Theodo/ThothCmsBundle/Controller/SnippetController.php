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
use Theodo\ThothCmsBundle\Form\SnippetType;

class SnippetController extends Controller
{

    /**
     * Snippet list
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     */
    public function indexAction()
    {
        $snippets = $this->get('thoth.content_repository')->findAll('snippet');

        return $this->render('TheodoThothCmsBundle:Snippet:index.html.twig',
                array('snippets' => $snippets)
                );
    }

    /**
     * Snippet edit
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     * @since 2011-06-29 cyrillej ($hasErrors, copied from PageController by vincentg)
     * @since 2011-07-06 mathieud ($hasErrors deleted)
     */
    public function editAction($id)
    {
        $snippet = null;
        if ($id) {
            $snippet = $this->get('thoth.content_repository')->findOneById($id, 'snippet');
        }
        $form = $this->createForm(new SnippetType(), $snippet);
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                // remove twig cached file
                if ($snippet) {
                    $this->get('thoth.caching')->invalidate('snippet:'.$snippet->getName());
                }

                //save snippet
                $snippet = $form->getData();
                $this->get('thoth.content_repository')->save($snippet);

                $this->get('thoth.caching')->warmup('snippet:'.$snippet->getName());

                // Set redirect route
                $redirect = $this->redirect($this->generateUrl('snippet_list'));
                if ($request->get('save-and-edit'))
                {
                    $redirect = $this->redirect($this->generateUrl('snippet_edit', array('id' => $snippet->getId())));
                }

                return $redirect;
            }
        }

        return $this->render('TheodoThothCmsBundle:Snippet:edit.html.twig',
                array(
                    'snippet' => $snippet,
                    'form' => $form->createView(),
                  )
                );
    }

    /**
     * Supprime un snippet
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-21
     * @param integer $id
     */
    public function removeAction($id)
    {
        $snippet = $snippet = $this->get('thoth.content_repository')->findOneById($id, 'snippet');

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $snippet = $this->get('thoth.content_repository')->remove($snippet);

            return $this->redirect($this->generateUrl('snippet_list'));
        }

        return $this->render('TheodoThothCmsBundle:Snippet:remove.html.twig',
                array(
                  'snippet' => $snippet
                ));
    }
}
