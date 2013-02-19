<?php
/*
 * This file is part of the Roger CMS Bundle
 *
 * (c) Theodo <contact@theodo.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Theodo\RogerCmsBundle\Tests\Extensions;

use Theodo\RogerCmsBundle\Tests\Test as TestCase;
use Theodo\RogerCmsBundle\Extensions\Twig\TwigLoaderRepository;

/**
 * TwigLoaderRepository extension test class.
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
class TwigLoaderRepositoryTest extends TestCase
{
    /**
     * @var Theodo\RogerCmsBundle\Extensions\Twig\TwigLoaderRepository
     */
    protected static $twigLoader;

    /**
     * Test page status
     *
     * @group functional
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-20
     */
    public function testGetSource()
    {
        static::createRogerKernel();

        // Load "test" entity manager
        static::$twigLoader = static::$kernel->getContainer()
            ->get('theodo_roger_cms.twig.loader');

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

    /**
     * @expectedException \Twig_Error_Loader
     * @dataProvider getUnsupportedTemplateNames
     */
    public function testThrowsExceptionForStandardTemplates($name)
    {
        $repository = $this->getMock('Theodo\RogerCmsBundle\Repository\ContentRepositoryInterface');
        $repository->expects($this->never())
            ->method('getSourceByNameAndType');

        $loader = new TwigLoaderRepository($repository);

        $loader->getSource($name);
    }

    public function getUnsupportedTemplateNames()
    {
        return array(
            array('AcmeDemoBundle:Default:index.html.twig'),
            array('AcmeDemoBundle::layout.html.twig'),
            array('::base.html.twig'),
        );
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
}
