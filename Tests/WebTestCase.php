<?php
/**
 * WebTestCase class
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 * @since 20/04/12
 */
namespace Theodo\RogerCmsBundle\Tests;

use Theodo\RogerCmsBundle\Tests\Test;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

abstract class WebTestCase extends Test
{
    protected static $fixtureDir;

    protected function setUp()
    {
        parent::setUp();

        static::$fixtureDir  = __DIR__.'/Fixtures/app';

        $filesystem = new \Symfony\Component\Filesystem\Filesystem();
        $filesystem->remove(static::$fixtureDir.'/cache');
        $filesystem->remove(static::$fixtureDir.'/logs');
        $filesystem->mkdir(static::$fixtureDir.'/cache');
        $filesystem->mkdir(static::$fixtureDir.'/logs');
    }


    static protected function getKernelClass()
    {
        require_once static::$fixtureDir.'/AppTestKernel.php';

        return 'Theodo\RogerCmsBundle\Tests\Fixtures\AppTestKernel';
    }
}
