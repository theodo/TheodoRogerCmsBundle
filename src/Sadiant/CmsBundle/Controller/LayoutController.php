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
use Sadiant\CmsBundle\Form\LayoutType;

class LayoutController extends BaseController
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
     * Liste des Layouts
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     */
    public function indexAction()
    {
        $layouts = $this->getEM()
            ->getRepository('SadiantCmsBundle:Layout')
            ->findAll();

        return $this->render('SadiantCmsBundle:Layout:index.html.twig',
                array('layouts' => $layouts)
                );
    }

    /**
     * Nouveau Layout
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     */
    public function newAction()
    {
        $form = $this->createForm(new LayoutType());
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $layout = $form->getData();
                $this->getEM()->persist($layout);
                $this->getEM()->flush();

                // Set redirect route
                $redirect = $this->redirect($this->generateUrl('layout_list'));
                if ($request->get('save-and-edit'))
                {
                    $redirect = $this->redirect($this->generateUrl('layout_edit', array('id' => $layout->getId())));
                }

                return $redirect;
            }
        }

        return $this->render('SadiantCmsBundle:Layout:edit.html.twig',
                array(
                    'title' => 'New layout',
                    'form' => $form->createView()
                  )
                );
    }

    /**
     * TODO
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     * @param type $form
     * @param type $request
     */
    public function processForm($form, $request)
    {
    }

    /**
     * Update un Layout
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     */
    public function updateAction($id)
    {
        $layout = $this->getEM()
            ->getRepository('SadiantCmsBundle:Layout')
            ->findOneById($id);
        $form = $this->createForm(new LayoutType(), $layout);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {

                // remove twig cached file
                $this->removeCache($layout->getName(), 'layout');

                // save layout
                $layout = $form->getData();
                $this->getEM()->persist($layout);
                $this->getEM()->flush();

                // Set redirect route
                $redirect = $this->redirect($this->generateUrl('layout_list'));
                if ($request->get('save-and-edit'))
                {
                    $redirect = $this->redirect($this->generateUrl('layout_edit', array('id' => $layout->getId())));
                }

                return $redirect;
            }
        }

        return $this->redirect($this->generateUrl('layout_edit', array('id' => $layout->getId())));
    }

    /**
     * Edition d'un layout
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     * @param Int $id
     */
    public function editAction($id)
    {
        $layout = $this->getEM()
            ->getRepository('Sadiant\CmsBundle\Entity\Layout')
            ->findOneById($id);

        $form = $this->createForm(new LayoutType(), $layout);

        return $this->render('SadiantCmsBundle:Layout:edit.html.twig',
                array(
                    'title' => 'Edit '.$layout->getName(),
                    'layout' => $layout,
                    'form' => $form->createView()
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
        $layout = $this->getEM()
            ->getRepository('Sadiant\CmsBundle\Entity\Layout')
            ->findOneById($id);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $this->getEM()->remove($layout);
            $this->getEM()->flush();

            return $this->redirect($this->generateUrl('layout_list'));
        }

        return $this->render('SadiantCmsBundle:Layout:remove.html.twig',
                array(
                  'layout' => $layout
                ));
    }
}
