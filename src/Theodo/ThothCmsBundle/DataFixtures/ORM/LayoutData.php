<?php

namespace Theodo\ThothCmsBundle\DataFixtures\ORM;

use Theodo\ThothCmsBundle\Entity\Layout;
use Theodo\ThothCmsBundle\Repository\LayoutRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LayoutData implements FixtureInterface
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
        $layout1 = new Layout();
        $layout1->setName('normal');
        $layout1->setContent(<<<EOF
<html>
<head>
    <title>{% block title %}No title{% endblock %}</title>
</head>
<body>
  <div id="container">
    {% block content %}{% endblock %}
  </div>
  
  <hr />
  <div id="footer">
    {% block footer %}{% endblock %}
  </div>
</body>
</html>
EOF
);
        $manager->persist($layout1);

        $layout2 = new Layout();
        $layout2->setName('normaljs');
        $layout2->setContent(<<<EOF
<head></head>
<body>
  <div id="container">
    {% block content %}{% endblock %}
  </div>

  {% block javascripts %}{% endblock %}
</body>
EOF
);
        $manager->persist($layout2);

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
