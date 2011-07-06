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
use Symfony\Component\Security\Core\SecurityContext;

use Theodo\ThothCmsBundle\Repository\UserRepository;
use Theodo\ThothCmsBundle\Form\UserPreferencesType;
use Theodo\ThothCmsBundle\Form\UserType;
use Theodo\ThothCmsBundle\Entity\User;

class UserController extends Controller
{
    /**
     * Access denied action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function accessDeniedAction()
    {
        // Set flash message
        $this->get('session')->setFlash('error', $this->get('translator')->trans('Access denied, you must have more privileges to perform this action.'));

        return $this->redirect($this->generateUrl('page_list'));
    }

    /**
     * Check user language
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function checkSessionLocaleAction($redirect_route)
    {
        // Retrieve user
        $user = $this->get('security.context')->getToken()->getUser();

        // Set locale
        $this->get('session')->setLocale($user->getLanguage());

        return $this->redirect($this->generateUrl($redirect_route));
    }

    /**
     * Login action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-24
     */
    public function loginAction()
    {
        // User already authenticated, redirect to page list
        if($this->get('session')->has('_security_main'))
        {
            return $this->redirect($this->generateUrl('page_list'));
        }

        // Get the login error if there is one
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
        {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        else
        {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('TheodoThothCmsBundle:User:login.html.twig', array(
            // Last username entered by the user
            'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }

    /**
     * User box
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-24
     */
    public function boxComponentAction()
    {
        // Retrieve connected user
        $user = $this->get('security.context')->getToken()->getUser();

        return $this->render('TheodoThothCmsBundle:User:box-component.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * List user action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-27
     */
    public function listAction()
    {

        // Retrieve users
        $users = $this->get('thoth.content_repository')->findAll('user');

        return $this->render('TheodoThothCmsBundle:User:list.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Remove user action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-27
     */
    public function removeAction($id)
    {
        // Retrieve request
        $request = $this->get('request');

        // Retrieve users
        $user = $user = $this->get('thoth.content_repository')->findOneById($id, 'user');

        // Request is post
        if ($request->getMethod() == 'POST' && !$user->getIsMainAdmin())
        {
            // Delete page
            $this->get('thoth.content_repository')->remove($user);

            // Set notice
            $this->get('session')->setFlash('notice', $this->get('translator')->trans('User "%user%" has been removed', array('%user%' => $user->getName())));

            return $redirect = $this->redirect($this->generateUrl('user_list'));
        }

        // Set flash notice
        if ($request->getMethod() == 'POST' && $user->getIsMainAdmin())
        {
            $this->get('session')->setFlash('error', $this->get('translator')->trans('Can not remove main admin'));
        }

        return $this->render('TheodoThothCmsBundle:User:remove.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Edit user preferences
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-24
     */
    public function preferencesAction()
    {
        // Retrieve connected user
        $user = $this->get('security.context')->getToken()->getUser();

        // Create form
        $form = $this->createForm(new UserPreferencesType(false), $user);

        return $this->render('TheodoThothCmsBundle:User:preferences.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Update user preferences
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-24
     * @since 2011-06-29 cyrillej (setLocale)
     * @since 2011-07-06 mathieud ($hasErrors deleted)
     */
    public function preferencesUpdateAction()
    {
        // Retrieve request
        $request = $this->get('request');

        // Retrieve connected user
        $user = $this->get('security.context')->getToken()->getUser();
        $user_password = $user->getPassword();

        // Create form
        $form = $this->createForm(new UserPreferencesType(false), $user);

        // Request is post
        if ($request->getMethod() == 'POST')
        {
            // Bind form
            $form->bindRequest($request);

            // Check form and save object
            if ($form->isValid())
            {
                $user = $form->getData();

                // Check password update
                if ($user->getPassword() && $user_password != $user->getPassword())
                {
                    // Encode password
                    if ($user->getPassword() && $user->getPasswordConfirm())
                    {
                        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                        $user->setPassword($password);
                    }
                }
                else
                {
                    $user->setPassword($user_password);
                }

                $this->get('thoth.content_repository')->save($user);

                // Set error
                $this->get('session')->setFlash('notice', $this->get('translator')->trans('Your preferences have been updated'));

                // Set locale
                $this->get('session')->setLocale($user->getLanguage());

                return $this->redirect($this->generateUrl('user_preferences'));
            }
            else
            {
                // Set error
                $this->get('session')->setFlash('error', $this->get('translator')->trans('Can not update your preferences due some errors'));
            }
        }

        return $this->render('TheodoThothCmsBundle:User:preferences.html.twig', array(
            'user'      => $user,
            'form'      => $form->createView(),
        ));
    }

    /**
     * Edit user
     *
     * @return string
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-27
     * @since 2011-07-06 mathieud (refactoring)
     */
    public function editAction($id)
    {
        // Retrieve user
        $user = new User();
        $user->setSalt(md5(time()));
        $old_password = null;
        if ($id) {
            $user = $user = $this->get('thoth.content_repository')->findOneById($id, 'user');
            $old_password = $user->getPassword();
        }

        // Create form
        $form = $this->createForm(new UserType(false), $user);

        // Retrieve request
        $request = $this->get('request');

        // Request is post
        if ($request->getMethod() == 'POST')
        {
            // Bind form
            $form->bindRequest($request);

            // Check form and save object
            if ($form->isValid())
            {
                $user = $form->getData();

                // Check password update
                if ($user->getPassword() && $old_password != $user->getPassword())
                {
                    // Encode password
                    if ($user->getPassword() && $user->getPasswordConfirm())
                    {
                        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                        $user->setPassword($password);
                    }
                }
                else
                {
                    $user->setPassword($old_password);
                }
                $this->get('thoth.content_repository')->save($user);

                // Set notice
                if ($old_password == null) {
                    $this->get('session')->setFlash('notice', $this->get('translator')->trans('User "%user%" has been created', array('%user%' => $user->getName())));
                }
                else {
                    $this->get('session')->setFlash('notice', $this->get('translator')->trans('User "%user%" has been updated', array('%user%' => $user->getName())));
                }

                return $this->redirect($this->generateUrl('user_edit', array('id' => $user->getId())));
            }
            else
            {
                // Construct errors
                $errors = array();
                foreach ($form->getErrors() as $error)
                {
                    array_push($errors, $this->get('translator')->trans($error->getMessageTemplate()));
                }

                if ($old_password == null) {
                    $this->get('session')->setFlash(
                        'error',
                        $this->get('translator')->trans(
                            'Can not save user due some errors%errors%',
                             array(
                                '%errors%' => count($errors) ? ': '.implode(', ', $errors).'.' : ''
                             )
                        )
                    );
                }
                else {
                    $this->get('session')->setFlash(
                        'error',
                        $this->get('translator')->trans(
                            'Can not save user %user% due some errors%errors%',
                             array(
                                '%user' => $user->getName(),
                                '%errors%' => count($errors) ? ': '.implode(', ', $errors).'.' : ''
                             )
                        )
                    );
                }
                // Set error
                
            }
        }

        return $this->render(
            'TheodoThothCmsBundle:User:edit.html.twig',
            array(
                'form'      => $form->createView(),
                'user'      => $user
            )
        );
    }
}
