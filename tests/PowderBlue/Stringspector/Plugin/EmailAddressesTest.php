<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace Tests\PowderBlue\Stringspector\Plugin\EmailAddresses;

use PowderBlue\Stringspector\Plugin\EmailAddresses;
use PowderBlue\Stringspector\Stringspector;

class Test extends \PHPUnit_Framework_TestCase
{
    public function testImplementsThePluginInterface()
    {
        $reflectionClass = new \ReflectionClass('PowderBlue\Stringspector\Plugin\EmailAddresses');
        $this->assertTrue($reflectionClass->implementsInterface('PowderBlue\Stringspector\Plugin\PluginInterface'));
    }

    public static function providesStringsContainingEmailAddresses()
    {
        return array(
            array(false, 'Hello, World!'),
            array(true, 'dan@powder-blue.com'),
            array(true, ' dan@powder-blue.com '),
            array(true, 'My email address is dan@powder-blue.com.'),
            array(true, 'You can use either dan@powder-blue.com or dan@seetheworld.com.'),
        );
    }

    /**
     * @dataProvider providesStringsContainingEmailAddresses
     */
    public function testFoundReturnsTrueIfThereIsAnEmailAddressInTheString($expected, $input)
    {
        $stringspector = new Stringspector($input);

        $emailAddresses = new EmailAddresses();
        $emailAddresses->setStringspector($stringspector);

        $this->assertSame($expected, $emailAddresses->found());
    }

    public static function providesObfuscatedEmailAddresses()
    {
        return array(
            array('dan@***************', 'dan@powder-blue.com'),
            array('My email address is dan@***************.', 'My email address is dan@powder-blue.com.'),
            array(
                'You can use either dan@*************** or dan@***************.',
                'You can use either dan@powder-blue.com or dan@seetheworld.com.',
            ),
        );
    }

    /**
     * @dataProvider providesObfuscatedEmailAddresses
     */
    public function testObfuscateObfuscatesAllEmailAddressesInTheString($expected, $input)
    {
        $stringspector = new Stringspector($input);

        $emailAddresses = new EmailAddresses();
        $emailAddresses->setStringspector($stringspector);
        $emailAddresses->obfuscate();

        $this->assertSame($expected, $stringspector->getString());
    }
}
