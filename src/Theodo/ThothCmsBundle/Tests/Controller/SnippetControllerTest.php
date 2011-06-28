<?php

namespace Theodo\ThothCmsBundle\Tests\Controller;

require_once __DIR__ . '/../../../../../app/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Theodo\ThothCmsBundle\Repository\SnippetRepository;

class SnippetControllerTest extends WebTestCase
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
        $this->logout($client);

        // Retrieve crawler
        $crawler = $client->request('GET', '/admin');

        // Select the login form
        $form = $crawler->filterXPath('//input[@name="login"]')->form();

        // Submit the form with valid credentials
        $crawler = $client->submit(
            $form,
            array(
                '_username'    => $username,
                '_password'    => $password,
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
        return $client->request('GET', '/admin/logout');
    }

    /**
     * Test snippet list
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testList()
    {
        print_r("\n> SnippetController - Test list action");

        $client = $this->createClient();
        $crawler = $this->login($client);
        $crawler = $client->request('GET', '/admin/snippets');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Snippets.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*theodo.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Remove.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test new action
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testNew()
    {
        print_r("\n> SnippetController - Test new action");

        $client = $this->createClient();
        $crawler = $this->login($client);
        $crawler = $client->request('GET', '/admin/snippets/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*New Snippet.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test snippet edit
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-22
     */
    public function testEdit()
    {
        print_r("\n> SnippetController - Test edit action");

        $client = $this->createClient();
        $crawler = $this->login($client);
        $crawler = $client->request('GET', '/admin/snippets/1/edit');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Edit.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*name.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test snippet update
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-23
     */
    public function testUpdate()
    {
        print_r("\n> SnippetController - Test update action");

        $client = $this->createClient();
        $crawler = $this->login($client);
        $crawler = $client->request('GET', '/admin/snippets/1');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*Edit.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test snippet remove
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-23
     */
    public function testRemove()
    {
        print_r("\n> SnippetController - Test remove action");

        $client = $this->createClient();
        $crawler = $this->login($client);
        $crawler = $client->request('GET', '/admin/snippets/1/remove');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*permanently remove.*/', $client->getResponse()->getContent());

        $this->logout($client);
    }

    /**
     * Test workflow
     *
     * @author Mathieu Dähne <mathieud@theodo.fr>
     * @since 2011-06-23
     */
    public function testWorkflow()
    {
        print_r("\n> SnippetController - Test workflow");

        // Start transaction
        $this->em->getConnection()->beginTransaction();

        $client = $this->createClient();
        $crawler = $this->login($client);
        $crawler = $client->request('GET', '/admin/snippets');

        //Test status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Retrieve "Add snippet" link and click
        $link = $crawler->filterXPath('//a[@id="new-snippet"]')->link();
        $crawler = $client->click($link);

        // Test status and content
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*New Snippet.*/', $client->getResponse()->getContent());

        // Retrieve form
        $form = $crawler->filterXPath('//input[@name="save-and-edit"]')->form();

        // Submit form with errors
        $crawler = $client->submit($form, array());
        $crawler = $client->request('POST', $form->getUri());

        // Test return
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*admin\/snippets\/new$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*New Snippet.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*This value should not be blank.*/', $client->getResponse()->getContent());

        // Submit valid form
        $crawler = $client->submit($form, array(
                    'snippet[name]' => 'Functional test',
                    'snippet[content]' => 'Functional test',
                ));

        // Test return
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*admin\/snippets\/.*\/edit$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*Edit.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Functional test.*/', $client->getResponse()->getContent());

        // Update Form
        $form = $crawler->filterXPath('//input[@name="save-and-edit"]')->form();
        $form['snippet[content]'] = 'Update test';

        // Submit the form
        $crawler = $client->submit($form);

        // Test return
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertRegexp('/.*admin\/snippets\/.*\/edit$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*Edit.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Functional test.*/', $client->getResponse()->getContent());
        $this->assertRegexp('/.*Update test.*/', $client->getResponse()->getContent());

        // Back to admin homepage
        $link = $crawler->filterXPath('//a[@class="list-snippet"]')->link();
        $crawler = $client->click($link);

        // Test status and content
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*admin\/snippets$/', $client->getRequest()->getUri());
        $this->assertRegexp('/.*Functional test.*/', $client->getResponse()->getContent());

        // Test remove
        $link = $crawler->filterXPath('//a[@id="remove-snippet-3"]')->link();
        $crawler = $client->click($link);

        // Test status and content
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp('/.*permanently remove.*/', $client->getResponse()->getContent());

        // Retrieve the delete form
        $form = $crawler->filterXPath('//input[@type="submit"]')->form();

        // Submit the form
        $crawler = $client->submit($form);

        // Test return
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertRegexp('/.*admin\/snippets$/', $client->getRequest()->getUri());

        $this->em->getConnection()->rollBack();

        $this->logout($client);
    }
}
