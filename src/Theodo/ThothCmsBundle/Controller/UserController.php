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
     *
     * @return EntityManager
     *
     * @author fabricbe
     * @since 2011-07-08
     */
    public function getEntityManager()
    {

        return $this->get('doctrine.orm.entity_manager');
    }
    /**
     *
     * @return UserRepository
     * @author fabricbe
     * @since 2011-07-08
     */
    public function getUserRepository()
    {
        return $this->getEntityManager()->getRepository('TheodoThothCmsBundle:User');
    }

    /**
     * Access denied action
     *
     * @return Response
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
     * @return Response
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
     * @return Response
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-24
     */
    public function loginAction()
    {
        // User already authenticated, redirect to page list
        if($this->get('session')->has('_security_main')) {
            return $this->redirect($this->generateUrl('page_list'));
        }

        // Get the login error if there is one
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        else {
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
     * @return Response
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
     * @return Response
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-27
     */
    public function listAction()
    {
        // Retrieve users
        $users = $this->getUserRepository()->findAll('user');

        return $this->render('TheodoThothCmsBundle:User:list.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Remove user action
     *
     * @param integer $id
     * @return Response
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-27
     */
    public function removeAction($id)
    {
        // Retrieve request
        $request = $this->get('request');

        // Retrieve users
        $user = $this->getUserRepository()->findOneById($id, 'user');

        // Request is post
        if ($request->getMethod() == 'POST' && !$user->getIsMainAdmin()) {
            // Delete page

            $this->getEntityManager()->remove($user);
            $this->getEntityManager()->flush();

            // Set notice
            $this->get('session')->setFlash('notice', $this->get('translator')->trans('User "%user%" has been removed', array('%user%' => $user->getName())));

            return $redirect = $this->redirect($this->generateUrl('user_list'));
        }

        // Set flash notice
        if ($request->getMethod() == 'POST' && $user->getIsMainAdmin()) {
            $this->get('session')->setFlash('error', $this->get('translator')->trans('Can not remove main admin'));
        }

        return $this->render('TheodoThothCmsBundle:User:remove.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Retrieve user
     *
     * @param integer $id
     * @param boolean $self
     * @return User
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-07
     */
    public function retrieveUser($id, $self)
    {
        $user = new User();
        $user->setSalt(md5(time()));
        if ($self) {
            $user = $this->get('security.context')->getToken()->getUser();
        }
        else if ($id) {
            $user = $this->getUserRepository()->findOneById($id, 'user');
        }

        return $user;
    }

    /**
     * Set the success flash message
     *
     * @param integer $id
     * @param boolean $self
     * @param User $user
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-07
     */
    public function setEditSuccessSession($id, $self, $user)
    {
        if ($self) {
            // Set success message
            $this->get('session')->setFlash('notice', $this->get('translator')->trans('Your preferences have been updated'));
        }
        // Set notice
        else if ($id == null) {
            $this->get('session')->setFlash('notice', $this->get('translator')->trans('User "%user%" has been created', array('%user%' => $user->getName())));
        }
        else {
            $this->get('session')->setFlash('notice', $this->get('translator')->trans('User "%user%" has been updated', array('%user%' => $user->getName())));
        }
    }

    /**
     * Set the error flash message
     *
     * @param integer $id
     * @param boolean $self
     * @param Form $form
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-07-07
     */
    public function setEditErrorMessage($id, $self, $form)
    {
        // Construct errors
        $errors = array();
        foreach ($form->getErrors() as $error) {
            array_push($errors, $this->get('translator')->trans($error->getMessageTemplate()));
        }

        // Set error
        if ($self) {
            $message = $this->get('translator')->trans(
                    'Can not update your preferences due to some errors%errors%',
                    array(
                        '%errors%' => count($errors) ? ': '.implode(', ', $errors).'.' : ''
                     )
              );
        }
        else if (!$id) {
            $message = $this->get('translator')->trans(
                    'Can not save user due to some errors%errors%',
                     array(
                        '%errors%' => count($errors) ? ': '.implode(', ', $errors).'.' : ''
                     )
                );
        }
        else {
            $message = $this->get('translator')->trans(
                    'Can not save user %user% due to some errors%errors%',
                     array(
                        '%user' => $user->getName(),
                        '%errors%' => count($errors) ? ': '.implode(', ', $errors).'.' : ''
                     )
                );
        }

        $this->get('session')->setFlash('error', $message);
    }

    /**
     * Edit user
     *
     * @param integer $id
     * @param boolean $self
     * @return Response
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-27
     * @since 2011-07-06 mathieud (refactoring)
     */
    public function editAction($id, $self)
    {
        // Retrieve user
        $user = $this->retrieveUser($id, $self);
        $old_password = $user->getPassword();

        $action = 'edit';
        if ($self) {
            $action = 'preferences';
        }

        // Create form
        $form = $this->createForm(new UserType(false), $user);

        // Retrieve request
        $request = $this->get('request');

        // Request is post
        if ($request->getMethod() == 'POST') {
            // Bind form
            $form->bindRequest($request);

            // Check form and save object
            if ($form->isValid()) {
                $user = $form->getData();

                // TODO FOSUserBundle
                // Check password update
                if ($user->getPassword() && $old_password != $user->getPassword()) {
                    // Encode password
                    if ($user->getPassword()) {
                        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                        $user->setPassword($password);
                    }
                }
                else {
                    $user->setPassword($old_password);
                }

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                // Set locale
                if ($self) {
                    $this->get('session')->setLocale($user->getLanguage());
                }

                // Set success flash message
                $this->setEditSuccessSession($id, $self, $user);

                return $this->redirect($this->generateUrl('user_'.$action, array('id' => $user->getId())));
            }
            else {
                // Set error flash message
                $this->setEditErrorMessage($id, $self, $form);
            }
        }

        return $this->render(
            'TheodoThothCmsBundle:User:'.$action.'.html.twig',
            array(
                'form'      => $form->createView(),
                'user'      => $user
            )
        );
    }
}
