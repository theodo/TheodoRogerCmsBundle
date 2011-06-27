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
use Symfony\Component\Security\Core\SecurityContext;

use Sadiant\CmsBundle\Repository\UserRepository;
use Sadiant\CmsBundle\Form\UserPreferencesType;
use Sadiant\CmsBundle\Form\UserType;
use Sadiant\CmsBundle\Entity\User;

class UserController extends Controller
{
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

        return $this->render('SadiantCmsBundle:User:login.html.twig', array(
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
        
        return $this->render('SadiantCmsBundle:User:box-component.html.twig', array(
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
        // Retrieve EntityManager
        $em = $this->getDoctrine()->getEntityManager();
        
        // Retrieve users
        $users = $em->getRepository('SadiantCmsBundle:User')->findAll();

        return $this->render('SadiantCmsBundle:User:list.html.twig', array(
            'users' => $users,
        ));
    }
    
    /**
     * Edit user action
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-27
     */
    public function editAction()
    {
        // Retrieve request
        $request = $this->get('request');
        
        // Retrieve EntityManager
        $em = $this->getDoctrine()->getEntityManager();

        // Retrieve user
        $user = $em->getRepository('SadiantCmsBundle:User')->findOneBy(array('id' => $request->get('id')));

        // Create form
        $form = $this->createForm(new UserType(false), $user);

        return $this->render('SadiantCmsBundle:User:edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));
    }
    
    /**
     * New user action
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-27
     */
    public function newAction()
    {
        // Retrieve request
        $request = $this->get('request');
        
        // Retrieve EntityManager
        $em = $this->getDoctrine()->getEntityManager();

        // Retrieve user
        $user = new User();
        $user->setSalt(md5(time()));

        // Create form
        $form = $this->createForm(new UserType(), $user);
        
        // Request is post, bind and save form
        if ($request->getMethod() == 'POST')
        {
            // Bind form
            $form->bindRequest($request);

            // Check form
            if ($form->isValid())
            {
                // Perform some action, such as save the object to the database
                $user = $form->getData();

                // Set password
                $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
                
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('user_list'));
            }
        }

        return $this->render('SadiantCmsBundle:User:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * Remove user action
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-27
     */
    public function removeAction()
    {
        // Retrieve request
        $request = $this->get('request');
        
        // Retrieve EntityManager
        $em = $this->getDoctrine()->getEntityManager();
        
        // Retrieve users
        $user = $em->getRepository('SadiantCmsBundle:User')->findOneBy(array('id' => $request->get('id')));
        
        // Request is post
        if ($request->getMethod() == 'POST' && !$user->getIsMainAdmin())
        {
            // Delete page
            $em->remove($user);
            $em->flush();
            
            return $redirect = $this->redirect($this->generateUrl('user_list'));
        }

        return $this->render('SadiantCmsBundle:User:remove.html.twig', array(
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
        
        return $this->render('SadiantCmsBundle:User:preferences.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Update user preferences
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-24
     */
    public function preferencesUpdateAction()
    {
        // Retrieve request
        $request = $this->get('request');
        
        // Retrieve EntityManager
        $em = $this->getDoctrine()->getEntityManager();
        
        // Retrieve connected user
        $user = $this->get('security.context')->getToken()->getUser();

        // Create form
        $form = $this->createForm(new UserPreferencesType(false), $user);
        
        // Initialize form hasErros
        $hasErrors = false;
        
        // Request is post
        if ($request->getMethod() == 'POST')
        {
            // Bind form
            $form->bindRequest($request);
 
            // Check form and save object
            if ($form->isValid())
            {
                $user = $form->getData();
                
                // Set password
                $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
                
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('user_preferences'));
            }
            else
            {
                $hasErrors = true;
            }
        }
        
        return $this->render('SadiantCmsBundle:User:preferences.html.twig', array(
            'user'      => $user,
            'form'      => $form->createView(),
            'hasErrors' => $hasErrors
        ));
    }
    
    /**
     * Update user action
     * 
     * @return string
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-27
     */
    public function updateAction()
    {
        // Retrieve request
        $request = $this->get('request');

        // Retrieve EntityManager
        $em = $this->getDoctrine()->getEntityManager();
        
        // Retrieve user
        $user = $em->getRepository('SadiantCmsBundle:User')->findOneBy(array('id' => $request->get('id')));
        
        // Create form
        $form = $this->createForm(new UserType(false), $user);

        // Request is post
        if ($request->getMethod() == 'POST')
        {
            // Bind form
            $form->bindRequest($request);
 
            // Check form and save object
            if ($form->isValid())
            {
                $user = $form->getData();

                // Set password
                $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);

                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('user_edit', array('id' => $user->getId())));
            }
        }

        return $this->render(
            'SadiantCmsBundle:User:edit.html.twig',
            array(
                'form'      => $form->createView(),
                'user'      => $user
            )
        );
    }
}
