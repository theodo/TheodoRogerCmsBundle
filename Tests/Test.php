<?php
/*
 * This file is part of the Roger CMS Bundle.
 * Some parts of this file are copied form the ParisStreetPingPong application.
 *
 * (c) Benjamin Grandfond <benjamin.grandfond@gmail.com>
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\RogerCmsBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\DataFixtures\Loader as DataFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

/**
 * Test class.
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
abstract class Test extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected static $em;

    /**
     * @var Theodo\RogerCmsBundle\ContentRepositoryInterface
     */
    protected static $contentRepository;

    protected static $fixtureDir;

    protected function setUp()
    {
        parent::setUp();

        static::$fixtureDir = __DIR__.'/Fixtures/app';
    }

    /**
     * Creates a Kernel, generate the Doctrine schema and load the fixtures.
     *
     * @see Symfony\Bundle\FrameworkBundle\Test\WebTestCase::createKernel
     *
     * @param array $options An array of options
     */
    public static function createRogerKernel(array $options = array())
    {
        static::$kernel = static::createKernel($options);
        static::$kernel->boot();

        static::$contentRepository = static::$kernel->getContainer()->get('roger.content_repository');

        static::generateSchema();
        static::loadFixtures();
    }

    /**
     * Creates a Client.
     *
     * @param array $options An array of options to pass to the createKernel class
     * @param array $server  An array of server parameters
     *
     * @return Client A Client instance
     */
    protected static function createClient(array $options = array(), array $server = array())
    {
        static::createRogerKernel($options);

        $client = static::$kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return $client;
    }

    /**
     * Generate the schema.
     *
     * @throws Doctrine\DBAL\Schema\SchemaException
     */
    protected static function generateSchema()
    {
        static::$em = static::$kernel->getContainer()->get('doctrine')->getEntityManager();
        $metadatas = static::$em->getMetadataFactory()->getAllMetadata();

        $connection = static::$em->getConnection();
        $name = $connection->getDatabase();

        $connection->getSchemaManager()->dropDatabase($name);

        if (!empty($metadatas)) {
            $tool = new SchemaTool(static::$em);
            $tool->createSchema($metadatas);
        } else {
            throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }

    /**
     * Load the fixtures from DataFixtures dir.
     *
     * @throws InvalidArgumentException
     */
    protected static function loadFixtures()
    {
        $path = __DIR__.'/../DataFixtures/ORM';

        $loader = new DataFixturesLoader(static::$kernel->getContainer());
        $loader->loadFromDirectory($path);

        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new InvalidArgumentException(
                sprintf('Could not find any fixtures to load in: %s', $path)
            );
        }

        $purger = new ORMPurger(static::$em);
        $executor = new ORMExecutor(static::$em, $purger);
        $executor->execute($fixtures);
    }

    /**
     * EntityManager getter
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return static::$em;
    }

    /**
     * ContentRepository getter
     *
     * @return Theodo\RogerCmsBundle\Reposiroty\ContentRepositoryInterface
     */
    protected function getContentRepository()
    {
        return static::$contentRepository;
    }

    protected static function getKernelClass()
    {
        require_once static::$fixtureDir.'/AppTestKernel.php';

        return 'Theodo\RogerCmsBundle\Tests\Fixtures\AppTestKernel';
    }
}
