<?php

namespace Theodo\RogerCmsBundle\DataFixtures\ORM;

use Theodo\RogerCmsBundle\Entity\User;
use Theodo\RogerCmsBundle\Repository\UserRepository;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class UserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    
    /**
     * Load user fixtures
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-24
     */
    public function load($manager)
    {
        $factory = $this->container->get('security.encoder_factory');
        
        // Create admin user
        $user = new User();
        $user->setName('Theodore De Banville');
        $user->setUsername('admin');
        $user->setSalt(md5(time()));
        $user->setEmail('dev+admin@theodo.fr');
        $user->setIsMainAdmin(true);
        $user->getUserRoles()->add($this->getReference('admin-role'));
        
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword('admin', $user->getSalt());
        $user->setPassword($password);

        $manager->persist($user);
        
        // Create simple user
        $user1 = new User();
        $user1->setName('User');
        $user1->setUsername('user');
        $user1->setPassword('user');
        $user1->setSalt(md5(time()));
        $user1->setEmail('dev+user@theodo.fr');
        $user1->getUserRoles()->add($this->getReference('user-role'));
        
        $encoder = $factory->getEncoder($user1);
        $password = $encoder->encodePassword('user', $user1->getSalt());
        $user1->setPassword($password);
        
        $manager->persist($user1);
        
        // Create cient user
        $user2 = new User();
        $user2->setName('Client');
        $user2->setUsername('client');
        $user2->setPassword('client');
        $user2->setSalt(md5(time()));
        $user2->setEmail('dev+client@theodo.fr');
        $user2->getUserRoles()->add($this->getReference('client-role'));
        
        $encoder = $factory->getEncoder($user2);
        $password = $encoder->encodePassword('client', $user2->getSalt());
        $user2->setPassword($password);
        
        $manager->persist($user2);

        // Save users
        $manager->flush();
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
        return 4;
    }

}
