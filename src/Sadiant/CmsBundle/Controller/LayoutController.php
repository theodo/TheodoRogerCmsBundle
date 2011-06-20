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


    public function indexAction()
    {
        $layouts = $this->getEM()
            ->getRepository('Sadiant\CmsBundle\Entity\Layout')
            ->findAll();

        return $this->render('SadiantCmsBundle:Layout:index.html.twig',
                array('layouts' => $layouts)
                );
    }

    public function viewAction($id)
    {
        $layout = $this->getEM()
            ->getRepository('Sadiant\CmsBundle\Entity\Layout')
            ->findOneById($id);

        $form = $this->createForm(new LayoutType(), $layout);

        return $this->render('SadiantCmsBundle:Layout:view.html.twig',
                array(
                    'layout' => $layout,
                    'form' => $form->createView()
                  )
                );
    }
}
