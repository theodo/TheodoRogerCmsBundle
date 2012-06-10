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
 * TwigLoaderRepository extension test class.
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
namespace Theodo\RogerCmsBundle\Tests\Extensions;

require_once __DIR__.'/../Test.php';

use Theodo\RogerCmsBundle\Tests\Test as TestCase;
use Theodo\RogerCmsBundle\Extensions\Twig\TwigLoaderRepository;

class TwigLoaderRepositoryTest extends TestCase
{
    /**
     * @var Theodo\RogerCmsBundle\Extensions\Twig\TwigLoaderRepository
     */
    protected static $twigLoader;

    public function setUp()
    {
        static::createRogerKernel();

        // Load "test" entity manager
        static::$twigLoader = static::$kernel->getContainer()->get('roger.twig.loader');
    }

    /**
     * TwigLoader getter
     *
     * @return Theodo\RogerCmsBundle\Extensions\Twig\TwigLoaderRepository
     */
    protected function getTwigLoader()
    {
        return static::$twigLoader;
    }

    /**
     * Test page status
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function testGetSource()
    {
        $source = $this->getTwigLoader()->getSource('page:Homepage');
        $this->assertRegExp('/id="homepage"/', $source);

        $source = $this->getTwigLoader()->getSource('layout:normal');
        $this->assertRegExp('/<head>/', $source);

        $source = $this->getTwigLoader()->getSource('snippet:bonsoir');
        $this->assertRegExp('/Bonsoir !/', $source);

        try {
            $this->getTwigLoader()->getSource('doesnotexist');
            $this->fail('Exception missing');
        } catch (\Twig_Error_Loader $expected) {
            $this->assertTrue(true);
        }

        try {
            $this->getTwigLoader()->getSource('layout:doesnotexist');
            $this->fail('Exception missing');
        } catch (\Twig_Error_Loader $expected) {
            $this->assertTrue(true);
        }

        try {
            $this->getTwigLoader()->getSource('doesnotexist:bonsoir');
            $this->fail('Exception missing');
        } catch (\Twig_Error_Loader $expected) {
            $this->assertTrue(true);
        }
    }
}
