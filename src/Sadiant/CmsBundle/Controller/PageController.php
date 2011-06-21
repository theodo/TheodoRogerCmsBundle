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

use Twig_Loader_String;
use Twig_Error_Syntax;

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

        return $this->render('SadiantCmsBundle:Page:index.html.twig', array('pages' => $pages));
    }

    /**
     * Test for page display
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-21
     */
    public function visibleAction($slug)
    {
        $page = $this->getDoctrine()
                ->getEntityManager()
                ->getRepository('SadiantCmsBundle:Page')
                ->findOneBySlug($slug);

        //on modifie le loader de twig
        $twig_environment = $this->get('twig');
        $old_loader = $twig_environment->getLoader();
        $twig_environment->setLoader(new Twig_Loader_String());
        $twig_engine = $this->get('templating');

        //$layout = $page->getLayout()->getContent();
        $layout =  $this->getDoctrine()
                ->getEntityManager()
                ->getRepository('SadiantCmsBundle:Layout')
                ->findOneById(1)->getContent();
        $content = $page->getContent();

        $content = <<<EOF
{% extends '$layout' %}
$content
EOF;

        try
        {
          $response = $twig_engine->renderResponse($content);
          $twig_environment->setLoader($old_loader);
        }
        catch (Twig_Error_Syntax $e)
        {
          $twig_environment->setLoader($old_loader);
          throw $e;
        }

        return $response;
    }
}
