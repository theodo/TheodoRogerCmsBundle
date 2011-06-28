<?php

namespace Theodo\ThothCmsBundle\DataFixtures\ORM;

use Theodo\ThothCmsBundle\Entity\Page;
use Theodo\ThothCmsBundle\Repository\PageRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;

class PageData implements FixtureInterface
{
    /**
     * Load page fixtures
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function load($manager)
    {
        // Create new page (homepage)
        $page1 = new Page();
        $page1->setName('Homepage');
        $page1->setContent(<<<EOF
{% extends 'layout:normal' %}
{% block content %}
  <div id="homepage">
    <h2>Lorem Ipsum</h2>
    <p>
      <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
    </p>
  </div>
  {% include 'snippet:theodo' %}
{% endblock %}
EOF
);
        //$page1->setLayoutId(1);
        $page1->setSlug('homepage');
        $page1->setBreadcrumb('Homepage');
        $page1->setDescription("Cms homepage");
        $page1->setStatus(PageRepository::STATUS_PUBLISH);
        $page1->setPublishedAt(new \DateTime('now'));

        $manager->persist($page1);

        // Create new page (about)
        $page2 = new Page();
        $page2->setName('About');
        $page2->setContent(<<<EOF
<div id="about">
  <h2>About</h2>
  <p>
    Theodo CMS based on Symfony 2.0
    (c) Theodo www.theodo.fr
  </p>
</div>
EOF
);
        //$page2->setLayoutId(1);
        $page2->setSlug('about');
        $page2->setBreadcrumb('About');
        $page2->setDescription("About page");
        $page2->setStatus(PageRepository::STATUS_DRAFT);
        $page2->setParent($page1);
        $page2->setPublishedAt(new \DateTime('now'));

        $manager->persist($page2);

        // Create new page (Theodo)
        $page3 = new Page();
        $page3->setName('Theodo');
        $page3->setContent(<<<EOF
{% extends 'layout:normal' %}

{% block content %} 
<div id="theodo">
  <h2>Theodo</h2>
</div>
{% endblock %}

{% block footer %}
Copyright Theodo 2011
{% endblock %}
EOF
);
        $page3->setSlug('theodo');
        $page3->setBreadcrumb('Theodo');
        $page3->setDescription("Theodo page");
        $page3->setStatus(PageRepository::STATUS_PUBLISH);
        $page3->setParent($page1);
        $manager->persist($page3);

        // Create new page (Theodo team)
        $page4 = new Page();
        $page4->setName('Theodo team');
        $page4->setContent(<<<EOF
<div id="theodo-team">
  <h2>Theodo team</h2>
</div>
EOF
);
        $page4->setSlug('theodo-team');
        $page4->setBreadcrumb('Theodo team');
        $page4->setDescription("Theodo team page");
        $page4->setStatus(PageRepository::STATUS_DRAFT);
        $page4->setParent($page3);
        $manager->persist($page4);

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
        return 1;
    }

}
