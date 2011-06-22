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
use Sadiant\CmsBundle\Repository\PageRepository;
use Sadiant\CmsBundle\Form\PageType;
use Sadiant\CmsBundle\Entity\Page;

class PageController extends Controller
{
    /**
     * List pages
     *
     * @return string
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function indexAction()
    {
        // Retrieve pages
        $pages = $this->getDoctrine()->getEntityManager()->getRepository('SadiantCmsBundle:Page')->queryForMainPages()->getResult();

        return $this->render('SadiantCmsBundle:Page:index.html.twig',array('pages' => $pages));
    }

    /**
     * New page action
     * 
     * @return string
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-21
     */
    public function newAction()
    {
        // Retrieve request
        $request = $this->getRequest();

        // Retrieve EntityManager
        $em = $this->getDoctrine()->getEntityManager();
        
        // Retrieve parent page
        $parent_page = $em->getRepository('SadiantCmsBundle:Page')->findOneBy(array('id' => $request->get('id')));

        // Create new page
        $page = new Page();
        $page->setParentId($parent_page->getId());
        
        // Create form
        $form = $this->createForm(new PageType($em), $page);

        return $this->render(
            'SadiantCmsBundle:Page:edit.html.twig',
            array(
                'form' => $form->createView(),
                'parent_page' => $parent_page
            )
        );
    }
    
    /**
     * Edit page action
     * 
     * @return string
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-21
     */
    public function editAction()
    {

    }
    
    /**
     * Remove page action
     * 
     * @return string
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-21
     */
    public function removeAction()
    {

    }
}
