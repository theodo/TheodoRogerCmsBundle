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

class LayoutController extends Controller
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
                // perform some action, such as save the object to the database
                $layout = $form->getData();
                $this->getEM()->persist($layout);
                $this->getEM()->flush();

                return $this->redirect($this->generateUrl('layout_edit', array('id' => $layout->getId())));
            }
        }

        return $this->render('SadiantCmsBundle:Layout:edit.html.twig',
                array(
                    'title' => 'Nouveau Layout',
                    'form' => $form->createView()
                  )
                );
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
                $layout = $form->getData();

                $this->getEM()->persist($layout);
                $this->getEM()->flush();

                return $this->redirect($this->generateUrl('layout_edit', array('id' => $layout->getId())));
            }
        }
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
                    'title' => 'Edition '.$layout->getName(),
                    'layout' => $layout,
                    'form' => $form->createView()
                  )
                );
    }
}
