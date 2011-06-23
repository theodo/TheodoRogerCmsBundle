<?php

namespace Sadiant\CmsBundle\DataFixtures\ORM;

use Sadiant\CmsBundle\Entity\Snippet;
use Sadiant\CmsBundle\Repository\SnippetRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;

class SnippetData implements FixtureInterface
{
    public function load($manager)
    {
      
        // Create new page (homepage)
        $snippet1 = new Snippet();
        $snippet1->setName('bonsoir');
        $snippet1->setContent(<<<EOF
Bonsoir !
EOF
);
        $manager->persist($snippet1);

        $snippet2 = new Snippet();
        $snippet2->setName('theodo');
        $snippet2->setContent(<<<EOF
 <a href="http://www.theodo.fr alt="">Theodo</a>
EOF
);
        $manager->persist($snippet2);

        // Save snippet
        $manager->flush();
    }
}
