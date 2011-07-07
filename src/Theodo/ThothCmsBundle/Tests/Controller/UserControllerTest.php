<?php

namespace Theodo\ThothCmsBundle\Tests\Controller;

require_once __DIR__ . '/../../../../../app/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Theodo\ThothCmsBundle\Repository\UserRepository;

class UserControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        // Load and boot kernel
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        // Load "test" entity manager
        $this->em = $kernel->getContainer()->get('doctrine')->getEntityManager('test');
    }

    /**
     * User connection
     * 
     * @return Crawler
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-24
     */
    protected function login($client, $username = 'admin', $password = 'admin')
    {
        // Retrieve crawler
        $crawler = $client->request('GET', '/cms');

        // Select the login form
        $form = $crawler->filterXPath('//input[@name="login"]')->form();

        // Submit the form with valid credentials
        $crawler = $client->submit(
                        $form, array(
                    '_username' => $username,
                    '_password' => $password,
                    '_remember_me' => true
                        )
        );

        // Response should be success
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        return $crawler;
    }

    /**
     * Logout user
     * 
     * @return Crawler
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-24
     */
    protected function logout($client)
    {
        return $client->request('GET', '/cms/logout');
    }

    /**
     * Test index action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testList()
    {
        $client = $this->createClient();

        // Connect user
        $crawler = $this->login($client);

        $crawler = $client->request('GET', '/cms/users');

        print_r("\n> Test user list action");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Theodore De Banville.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Administrator.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Designer.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*admin.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*user.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test new action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testNew()
    {
        $client = $this->createClient();
        $crawler = $this->login($client);
        $crawler = $client->request('GET', '/cms/users/new');

        print_r("\n> Test user new action");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*New User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Gravatar.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test edit action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testEdit()
    {
        $client = $this->createClient();
        $crawler = $this->login($client);
        $crawler = $client->request('GET', '/cms/users/1/edit');

        print_r("\n> Test user edit action");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Edit User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Name.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Username.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test update action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testUpdate()
    {
        print_r("\n> Test user update action");

        $client = $this->createClient();
        $crawler = $this->login($client);
        $crawler = $client->request('GET', '/cms/users/1/update');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Edit User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Name.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Username.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test remove action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testRemove()
    {
        print_r("\n> Test user remove action");

        $client = $this->createClient();
        $crawler = $this->login($client);
        $crawler = $client->request('GET', '/cms/users/2/remove');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Remove User.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test workflow
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testWorkflow()
    {
        print_r("\n> Test user workflow");

        // Start transaction
        $this->em->getConnection()->beginTransaction();

        $client = $this->createClient();
        $crawler = $this->login($client);
        $crawler = $client->request('GET', '/cms/users');

        // Test status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Retrieve "New User" link and click
        $link = $crawler->filterXPath('//a[@id="link-new-user"]')->link();
        $crawler = $client->click($link);

        // Test status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test page content
        $this->assertRegexp('/.*New User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*cms\/users\/new$/', $client->getRequest()->getUri());

        // Retrieve form
        $form = $crawler->filterXPath('//input[@name="save"]')->form();

        // Submit form with errors
        $crawler = $client->submit($form, array());

        // Test return
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*cms\/users\/new$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*New User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Can not save user due to some errors*/', $client->getResponse()->getContent());

        // Submit valid form
        $crawler = $client->submit($form, array(
            'user[name]' => 'User de test',
            'user[username]' => 'userdetest',
            'user[email]' => 'userdetest@theodo.fr',
            'user[password][first]' => 'testpwd',
            'user[password][second]' => 'testpwd',
            'user[user_roles][1]' => true,
            'user[user_roles][2]' => true,
            'user[user_roles][3]' => true,
            'user[language]' => UserRepository::LANGUAGE_EN,
        ));

        // Test return
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertRegexp('/.*cms\/users/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*has been created.*/', $client->getResponse()->getContent());
//        $this->assertRegexp('/.*Administrator, Designer*/', $client->getResponse()->getContent());

        // Remove user
        $crawler = $client->request('GET', '/cms/users/4/remove');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Remove User de test*/', $client->getResponse()->getContent());

        // Retrieve form
        $form = $crawler->filterXPath('//input[@type="submit"]')->form();
        $crawler = $client->request('POST', $form->getUri());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertRegexp('/.*cms\/users/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*has been removed.*/', $client->getResponse()->getContent());

        // Tyr to remove  main admin user
        $crawler = $client->request('GET', '/cms/users/1/remove');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Remove Theodore De Banville*/', $client->getResponse()->getContent());
        $form = $crawler->filterXPath('//input[@type="submit"]')->form();
        $crawler = $client->request('POST', $form->getUri());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Can not remove main admin.*/', $client->getResponse()->getContent());

        $this->em->getConnection()->rollBack();

        $this->logout($client);
    }
}
