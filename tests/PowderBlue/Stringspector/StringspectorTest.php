<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace Tests\PowderBlue\Stringspector;

use PowderBlue\Stringspector\Stringspector;
use PowderBlue\Stringspector\Plugin\AbstractPlugin;

class StringspectorTest extends \PHPUnit_Framework_TestCase
{
    public function testIsConstructedUsingAString()
    {
        $instance1 = new Stringspector('Hello, World!');
        $this->assertSame('Hello, World!', $instance1->getString());

        $instance2 = new Stringspector();
        $this->assertSame('', $instance2->getString());
    }

    public function testGetstringReturnsTheStringSetUsingSetstring()
    {
        $stringspector = new Stringspector();
        $stringspector->setString('Hello, World!');

        $this->assertSame('Hello, World!', $stringspector->getString());
    }

    public function testSetpluginAddsAPlugin()
    {
        $plugin = new Plugin001();

        $stringspector = new Stringspector();
        $stringspector->setPlugin('emailAddresses', $plugin);

        $this->assertSame($plugin, $stringspector->getPlugin('emailAddresses'));
    }

    public function testSetpluginPassesTheStringspectorToTheSpecifiedPlugin()
    {
        $plugin = new Plugin003();

        $stringspector = new Stringspector();
        $stringspector->setPlugin('emailAddresses', $plugin);

        $this->assertSame($stringspector, $plugin->getStringspector());
    }

    public function testPluginsCanBeAccessedMagically()
    {
        $plugin = new Plugin002();

        $stringspector = new Stringspector();
        $stringspector->setPlugin('emailAddresses', $plugin);

        $this->assertSame($plugin, $stringspector->emailAddresses);
    }

    /**
     * @expectedException OutOfBoundsException
     * @expectedExceptionMessage The plugin "foo" does not exist.
     */
    public function testThrowsAnExceptionIfTheRequestedPluginDoesNotExist()
    {
        $stringspector = new Stringspector();
        $stringspector->getPlugin('foo');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage The plugin "bar" does not exist.
     */
    public function testThrowsAnExceptionIfTheMagicallyRequestedPluginDoesNotExist()
    {
        $stringspector = new Stringspector();
        $stringspector->bar;
    }
}

//@codingStandardsIgnoreStart
class Plugin001 extends AbstractPlugin
{
}

class Plugin002 extends AbstractPlugin
{
}

class Plugin003 extends AbstractPlugin
{
}
//@codingStandardsIgnoreEnd
