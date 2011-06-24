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
     * Edit user preferences
     * 
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date 2011-06-24
     */
    public function preferencesAction()
    {
        // Retrieve connected user
        $user = $this->get('security.context')->getToken()->getUser();
        
        return $this->render('SadiantCmsBundle:User:preferences.html.twig', array(
            'user' => $user
        ));
    }
}
