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
use Theodo\RogerCmsBundle\Form\MediaType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Backend media management controller
 *
 * @author Mathieu Dähne <mathieud@theodo.fr>
 * @author Cyrille Jouineau <cyrillej@theodo.fr>
 * @author Marek Kalnik <marekk@theodo.fr>
 * @author Fabrice Bernhard <fabriceb@theodo.fr>
 * @author Benjamin Grandfond <benjamin.grandfond@gmail.com>
 */
class MediaController extends Controller
{
    /**
     * Media list
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        if (false == $this->get('security.context')->isGranted('ROLE_ROGER_READ_CONTENT')) {
            throw new AccessDeniedException('You are not allowed to list medias.');
        }

        $medias = $this->get('roger.content_repository')->findAll('media');

        return $this->render('TheodoRogerCmsBundle:Media:index.html.twig',
            array('medias' => $medias)
        );
    }

    /**
     * Media edit
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
        $media = null;
        if ($id) {
            $media = $this->get('roger.content_repository')->findOneById($id, 'media');
        }
        $form = $this->createForm(new MediaType(), $media);
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            if (false == $this->get('security.context')->isGranted('ROLE_ROGER_WRITE_CONTENT')) {
                throw new AccessDeniedException('You are not allowed to edit this media.');
            }

            $form->bindRequest($request);

            if ($form->isValid()) {
                // save media
                $media = $form->getData();
                // TODO find way to force update without modifying the media
                if (null !== $media->file) {
                    $media->setPath(null);
                }
                $this->get('roger.content_repository')->save($media);

                // Set redirect route
                $redirect = $this->redirect($this->generateUrl('media_list'));
                if ($request->get('save-and-edit')) {
                    $redirect = $this->redirect($this->generateUrl('media_edit', array('id' => $media->getId())));
                }

                return $redirect;
            }
        }

        return $this->render(
            'TheodoRogerCmsBundle:Media:edit.html.twig',
            array(
                'media' => $media,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Media remove
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
        if (false == $this->get('security.context')->isGranted('ROLE_ROGER_DELETE_CONTENT')) {
            throw new AccessDeniedException('You are not allowed to delete this media.');
        }

        $media = $this->get('roger.content_repository')->findOneById($id, 'media');

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $media = $this->get('roger.content_repository')->remove($media);

            return $this->redirect($this->generateUrl('media_list'));
        }

        return $this->render('TheodoRogerCmsBundle:Media:remove.html.twig',
            array(
                'media' => $media
            )
        );
    }
}
