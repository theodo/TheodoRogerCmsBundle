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
 * Test class.
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */

namespace Theodo\RogerCmsBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\DoctrineFixturesBundle\Common\DataFixtures\Loader as DataFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class Test extends WebTestCase
{
    /**
     * Creates a Kernel, generate the Doctrine schema and load the fixtures.
     *
     * @see Symfony\Bundle\FrameworkBundle\Test\WebTestCase::createKernel
     * @param array $options An array of options
     */
    public static function createRogerKernel(array $options = array())
    {
        static::$kernel = static::createKernel($options);
        static::$kernel->boot();

        static::generateSchema();
        static::loadFixtures();
    }

    /**
     * Generate the schema.
     *
     * @throws Doctrine\DBAL\Schema\SchemaException
     *
     * @return void
     */
    static protected function generateSchema()
    {
        static::$entityManager = static::$kernel->getContainer()->get('doctrine')->getEntityManager();
        $metadatas = static::$entityManager->getMetadataFactory()->getAllMetadata();

        $connection = static::$entityManager->getConnection();
        $name = $connection->getDatabase();

        $connection->getSchemaManager()->dropDatabase($name);

        if (!empty($metadatas)) {
            $tool = new SchemaTool(static::$entityManager);
            $tool->createSchema($metadatas);
        } else {
            throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }

    /**
     * @static
     * @throws InvalidArgumentException
     * @return void
     */
    static protected function loadFixtures()
    {
        $path = __DIR__.'/DataFixtures/ORM';

        $loader = new DataFixturesLoader(static::$kernel->getContainer());
        $loader->loadFromDirectory($path);

        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new InvalidArgumentException(
                sprintf('Could not find any fixtures to load in: %s', $path)
            );
        }

        $purger = new ORMPurger(static::$entityManager);
        $executor = new ORMExecutor(static::$entityManager, $purger);
        $executor->execute($fixtures);
    }
}