<?php
/**
 * PHP-DI
 *
 * @link      http://php-di.org/
 * @copyright Matthieu Napoli (http://mnapoli.fr/)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace DI\Bridge\Symfony\Test\UnitTest;

use DI\Bridge\Symfony\SymfonyContainerBridge;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as SfContainerInterface;

class SymfonyContainerBridgeTest extends \PHPUnit_Framework_TestCase
{
    public function testHasFallback()
    {
        $wrapper = new SymfonyContainerBridge();

        $fallback = $this->getMockForAbstractClass(ContainerInterface::class);
        $fallback->expects($this->once())
            ->method('has')
            ->with('foo')
            ->will($this->returnValue(false));

        $wrapper->setFallbackContainer($fallback);

        $this->assertFalse($wrapper->has('foo'));
    }

    public function testGetFallback()
    {
        $wrapper = new SymfonyContainerBridge();

        $fallback = $this->getMockForAbstractClass(ContainerInterface::class);
        $fallback->expects($this->once())
            ->method('get')
            ->with('foo')
            ->will($this->returnValue('bar'));

        $wrapper->setFallbackContainer($fallback);

        $this->assertEquals('bar', $wrapper->get('foo'));
    }

    public function testGetNotFoundReturnNull()
    {
        $wrapper = new SymfonyContainerBridge();

        $wrapper->setFallbackContainer(ContainerBuilder::buildDevContainer());

        $this->assertNull($wrapper->get('foo', SfContainerInterface::NULL_ON_INVALID_REFERENCE));
    }

    /**
     * @expectedException \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function testGetNotFoundException()
    {
        $wrapper = new SymfonyContainerBridge();

        $wrapper->setFallbackContainer(ContainerBuilder::buildDevContainer());

        $this->assertNull($wrapper->get('foo'));
    }
}
