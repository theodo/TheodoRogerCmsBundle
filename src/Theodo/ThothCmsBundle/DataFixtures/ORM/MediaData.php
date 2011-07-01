<?php

namespace Theodo\ThothCmsBundle\DataFixtures\ORM;

use Theodo\ThothCmsBundle\Entity\Media;
use Theodo\ThothCmsBundle\Repository\MediaRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;

class MEdiaData implements FixtureInterface
{
    /**
     * Load media fixtures
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function load($manager)
    {
        // Create new page (homepage)
        $media1 = new Media();
        $media1->setName('image1');
        $media1->setPath('edjhgqerh.jpg');
        $manager->persist($media1);

        $media2 = new Media();
        $media2->setName('normaljs');
        $media2->setPath('drfigusdni.jpg');
        $manager->persist($media2);

        // Save pages
        $manager->flush();
    }

    /**
     * Retrieve the order number of current fixture
     *
     * @return integer
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function getOrder()
    {
        // The order in which fixtures will be loaded
        return 42;
    }

}
