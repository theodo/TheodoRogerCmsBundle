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

/**
 * This class regroups common convenience methods for backend controllers
 *
 * @author Marek Kalnik <marekk@theodo.fr>
 */
class BackendController extends Controller
{
    /**
     * Helper method for ContentRepository search
     *
     * @return \Theodo\RogerCmsBundle\Repository\ContentRepositoryInterface
     */
    protected function getContentRepository()
    {
        return $this->get('theodo_roger_cms.content_repository');
    }
}
