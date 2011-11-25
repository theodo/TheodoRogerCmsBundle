<?php

namespace Theodo\RogerCmsBundle\DataFixtures\ORM;

use Theodo\RogerCmsBundle\Entity\Role;
use Theodo\RogerCmsBundle\Repository\RoleRepository;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class RoleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
* Load role fixtures
*
* @author Vincent Guillon <vincentg@theodo.fr>
* @since 2011-06-24
*/
    public function load($manager)
    {
        // Create admin role
        $admin_role = new Role();
        $admin_role->setName(RoleRepository::ROLE_ADMIN);
        $manager->persist($admin_role);

        // Create designer role
        $designer_role = new Role();
        $designer_role->setName(RoleRepository::ROLE_DESIGNER);
        $manager->persist($designer_role);

        // Create client role
        $cm_role = new Role();
        $cm_role->setName(RoleRepository::ROLE_CONTENT_MANAGER);
        $manager->persist($cm_role);

        // Create visitor role
        $visitor_role = new Role();
        $visitor_role->setName(RoleRepository::ROLE_VISITOR);
        $manager->persist($visitor_role);

        // Save users
        $manager->flush();

        $this->addReference('admin-role', $admin_role);
        $this->addReference('designer-role', $designer_role);
        $this->addReference('content-manager-role', $cm_role);
        $this->addReference('visitor-role', $visitor_role);
    }

    /**
* Retrieve the order number of current fixture
*
* @return integer
* @author Vincent Guillon <vincentg@theodo.fr>
* @since 2011-06-24
*/
    public function getOrder()
    {
        // The order in which fixtures will be loaded
        return 3;
    }

}
