<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sadiant\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sadiant\CmsBundle\Form\SnippetType;

class SnippetController extends Controller
{
    /**
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEM()
    {
        return $this->get('doctrine')->getEntityManager();
    }

    /**
     * Liste des Snippets
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     */
    public function indexAction()
    {
        $snippets = $this->getEM()
            ->getRepository('SadiantCmsBundle:Snippet')
            ->findAll();

        return $this->render('SadiantCmsBundle:Snippet:index.html.twig',
                array('snippets' => $snippets)
                );
    }

    /**
     * Nouveau Snippet
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     */
    public function newAction()
    {
        $form = $this->createForm(new SnippetType());
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $snippet = $form->getData();
                $this->getEM()->persist($snippet);
                $this->getEM()->flush();

                return $this->redirect($this->generateUrl('snippet_edit', array('id' => $snippet->getId())));
            }
        }

        return $this->render('SadiantCmsBundle:Snippet:edit.html.twig',
                array(
                    'title' => 'Nouveau Snippet',
                    'form' => $form->createView()
                  )
                );
    }

    /**
     * Update un Snippet
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     */
    public function updateAction($id)
    {
        $snippet = $this->getEM()
            ->getRepository('SadiantCmsBundle:Snippet')
            ->findOneById($id);
        $form = $this->createForm(new SnippetType(), $snippet);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $snippet = $form->getData();
                $this->getEM()->persist($snippet);
                $this->getEM()->flush();

                return $this->redirect($this->generateUrl('snippet_edit', array('id' => $snippet->getId())));
            }
        }
    }

    /**
     * Edition d'un Snippet
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     * @param Int $id
     */
    public function editAction($id)
    {
        $snippet = $this->getEM()
            ->getRepository('SadiantCmsBundle:Snippet')
            ->findOneById($id);

        $form = $this->createForm(new SnippetType(), $snippet);

        return $this->render('SadiantCmsBundle:Snippet:edit.html.twig',
                array(
                    'title' => 'Edition '.$snippet->getName(),
                    'snippet' => $snippet,
                    'form' => $form->createView()
                  )
                );
    }
}
