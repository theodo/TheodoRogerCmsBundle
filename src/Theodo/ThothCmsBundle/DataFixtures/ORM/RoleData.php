<?php

namespace Theodo\ThothCmsBundle\DataFixtures\ORM;

use Theodo\ThothCmsBundle\Entity\Role;
use Theodo\ThothCmsBundle\Repository\RoleRepository;

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
        
        // Create user role
        $user_role = new Role();
        $user_role->setName(RoleRepository::ROLE_USER);
        $manager->persist($user_role);
        
        // Create client role
        $client_role = new Role();
        $client_role->setName(RoleRepository::ROLE_CLIENT);
        $manager->persist($client_role);

        // Save users
        $manager->flush();
        
        $this->addReference('admin-role', $admin_role);
        $this->addReference('user-role', $user_role);
        $this->addReference('client-role', $client_role);
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
