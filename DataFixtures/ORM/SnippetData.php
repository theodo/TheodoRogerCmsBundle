<?php

namespace Theodo\RogerCmsBundle\DataFixtures\ORM;

use Theodo\RogerCmsBundle\Entity\Snippet;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Data fixtures for Snippet model class
 */
class SnippetData implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        // Create new page (homepage)
        $snippet1 = new Snippet();
        $snippet1->setName('bonsoir');
        $snippet1->setContent(<<<EOF
Bonsoir !
EOF
);
        $snippet1->setCacheable(true);
        $snippet1->setPublic(false);
        $manager->persist($snippet1);

        $snippet2 = new Snippet();
        $snippet2->setName('theodo');
        $snippet2->setContent(<<<EOF
 <a id="theodo-link" href="http://www.theodo.fr">Theodo</a>
EOF
);
        $snippet2->setCacheable(false);
        $snippet2->setPublic(false);
        $manager->persist($snippet2);

        // Save snippet
        $manager->flush();
    }
}
