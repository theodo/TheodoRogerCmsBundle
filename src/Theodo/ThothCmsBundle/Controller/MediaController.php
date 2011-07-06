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
use Theodo\ThothCmsBundle\Form\MediaType;

class MediaController extends Controller
{
    /**
     * Liste des Medias
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-01
     */
    public function indexAction()
    {
        $medias = $this->get('thoth.content_repository')->findAll('media');

        return $this->render('TheodoThothCmsBundle:Media:index.html.twig',
                array('medias' => $medias)
                );
    }

    /**
     * Nouveau Media
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     */
    public function newAction()
    {
        $form = $this->createForm(new MediaType());
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $media = $form->getData();
                $this->get('thoth.content_repository')->save($media);

                // Set redirect route
                $redirect = $this->redirect($this->generateUrl('media_list'));
                if ($request->get('save-and-edit'))
                {
                    $redirect = $this->redirect($this->generateUrl('media_edit', array('id' => $media->getId())));
                }

                return $redirect;
            }
        }

        return $this->render('TheodoThothCmsBundle:Media:edit.html.twig',
                array(
                    'title' => 'New media',
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
     * Update un Media
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     * @since 2011-06-29 cyrillej ($hasErrors, copied from PageController by vincentg)
     */
    public function updateAction($id)
    {
        $media = $this->get('thoth.content_repository')->findOneById($id, 'media');
        $form = $this->createForm(new MediaType(), $media);
        $request = $this->get('request');

        // Initialize form hasErros
        $hasErrors = false;

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {

                // save media
                $media = $form->getData();
                $this->get('thoth.content_repository')->save($media);

                // Set redirect route
                $redirect = $this->redirect($this->generateUrl('media_list'));
                if ($request->get('save-and-edit'))
                {
                    $redirect = $this->redirect($this->generateUrl('media_edit', array('id' => $media->getId())));
                }

                return $redirect;
            }
            else
            {
                $hasErrors = true;
            }
        }

        return $this->render('TheodoThothCmsBundle:Media:edit.html.twig',
                array(
                    'title' => 'Edit '.$media->getName(),
                    'media' => $media,
                    'form' => $form->createView(),
                    'hasErrors' => $hasErrors
                  )
                );
    }

    /**
     * Edition d'un media
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-20
     * @param Int $id
     */
    public function editAction($id)
    {
        $media = $media = $this->get('thoth.content_repository')->findOneById($id, 'media');

        $form = $this->createForm(new MediaType(), $media);

        return $this->render('TheodoThothCmsBundle:Media:edit.html.twig',
                array(
                    'title' => 'Edit '.$media->getName(),
                    'media' => $media,
                    'form' => $form->createView()
                  )
                );
    }

    /**
     * Supprime un media
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-21
     * @param integer $id
     */
    public function removeAction($id)
    {
        $media = $media = $this->get('thoth.content_repository')->findOneById($id, 'media');

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $media = $this->get('thoth.content_repository')->remove($media);

            return $this->redirect($this->generateUrl('media_list'));
        }

        return $this->render('TheodoThothCmsBundle:Media:remove.html.twig',
                array(
                  'media' => $media
                ));
    }
}
