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

use Sadiant\CmsBundle\Extensions\Twig_Loader_Database;
use Twig_Error_Syntax;
use Twig_Loader_Array;

class FrontendController extends Controller
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
     * Test for page display
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-21
     */
    public function pageAction($slug)
    {
        if(!$slug)
        {
            $slug = 'homepage';
        }
        $page = $this->getDoctrine()
                ->getEntityManager()
                ->getRepository('SadiantCmsBundle:Page')
                ->findOneBySlug($slug);

        //on modifie le loader de twig
        $twig_environment = $this->get('twig');
        $old_loader = $twig_environment->getLoader();
        $twig_engine = $this->get('templating');

        $twig_environment->setLoader(new Twig_Loader_Database($this->getDoctrine()->getEntityManager()));
        try
        {
          $response = $twig_engine->renderResponse($page->getName());
          $twig_environment->setLoader($old_loader);
        }
        catch (Twig_Error $e)
        {
          $twig_environment->setLoader($old_loader);
          throw $e;
        }

        return $response;
    }
}
