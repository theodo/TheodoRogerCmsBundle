<?php
/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * User Controller test class.
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @author Mathieu Dähne <mathieud@theodo.fr>
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
namespace Theodo\RogerCmsBundle\Tests\Controller;

require_once __DIR__.'/../WebTestCase.php';

use Theodo\RogerCmsBundle\Tests\WebTestCase;
use Theodo\RogerCmsBundle\Repository\UserRepository;

class UserControllerTest extends WebTestCase
{
    /**
     * Test index action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testList()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/admin/users', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Theodore De Banville.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Administrator.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Content Manager.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Client.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Visitor.*/', $client->getResponse()->getContent());
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

        $crawler = $client->request('GET', '/admin/users/new', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*New User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Gravatar.*/', $client->getResponse()->getContent());
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

        $crawler = $client->request('GET', '/admin/users/1/edit', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Edit User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Name.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Username.*/', $client->getResponse()->getContent());
    }

    /**
     * Test update action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testUpdate()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/admin/users/1/update', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Edit User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Name.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Username.*/', $client->getResponse()->getContent());
    }

    /**
     * Test remove action
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testRemove()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/admin/users/2/remove', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Remove User.*/', $client->getResponse()->getContent());
    }

    /**
     * Test workflow
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function testWorkflow()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/admin/users', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));

        // Test status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Retrieve "New User" link and click
        $link = $crawler->filterXPath('//a[@id="link-new-user"]')->link();
        $crawler = $client->click($link);

        // Test status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test page content
        $this->assertRegexp('/.*New User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*admin\/users\/new$/', $client->getRequest()->getUri());

        // Retrieve form
        $form = $crawler->filterXPath('//input[@name="save"]')->form();

        // Submit form with errors
        $crawler = $client->submit($form, array());

        // Test return
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*admin\/users\/new$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*New User.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Can not save user due to some errors*/', $client->getResponse()->getContent());

        // Submit valid form
        $crawler = $client->submit($form, array(
            'user[name]'             => 'User de test',
            'user[username]'         => 'userdetest',
            'user[email]'            => 'userdetest@theodo.fr',
            'user[password][first]'  => 'testpwd',
            'user[password][second]' => 'testpwd',
            'user[user_roles][1]'    => true,
            'user[user_roles][2]'    => true,
            'user[user_roles][3]'    => true,
            'user[language]'         => UserRepository::LANGUAGE_EN,
        ));

        // Test return
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertRegexp('/.*admin\/users/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*has been created.*/', $client->getResponse()->getContent());
//        $this->assertRegexp('/.*Administrator, Designer*/', $client->getResponse()->getContent());

        // Remove user
        $crawler = $client->request('GET', '/admin/users/4/remove');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Remove User de test*/', $client->getResponse()->getContent());

        // Retrieve form
        $form = $crawler->filterXPath('//input[@type="submit"]')->form();
        $crawler = $client->request('POST', $form->getUri());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertRegexp('/.*admin\/users/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*has been removed.*/', $client->getResponse()->getContent());

        // Tyr to remove  main admin user
        $crawler = $client->request('GET', '/admin/users/1/remove');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Remove Theodore De Banville*/', $client->getResponse()->getContent());
        $form = $crawler->filterXPath('//input[@type="submit"]')->form();
        $crawler = $client->request('POST', $form->getUri());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Can not remove main admin.*/', $client->getResponse()->getContent());
    }
}
