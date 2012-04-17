<?php

namespace Theodo\RogerCmsBundle\DataFixtures\ORM;

use Theodo\RogerCmsBundle\Entity\Media;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MediaData implements FixtureInterface
{
    /**
     * Load media fixtures
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function load(ObjectManager $manager)
    {
        // Create new page (homepage)
        $media1 = new Media();
        $media1->setName('image1');
        $media1->setPath('picture.jpg');
        $manager->persist($media1);

        $media2 = new Media();
        $media2->setName('image2');
        $media2->setPath('picture2.jpg');
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
