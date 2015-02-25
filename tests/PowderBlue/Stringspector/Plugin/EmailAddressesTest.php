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
    private function createStringspector()
    {
        $reflectionClass = new \ReflectionClass('PowderBlue\Stringspector\Stringspector');
        return $reflectionClass->newInstanceArgs(func_get_args());
    }

    private function createEmailAddresses(Stringspector $stringspector)
    {
        $emailAddresses = new EmailAddresses();
        $emailAddresses->setStringspector($stringspector);
        return $emailAddresses;
    }

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
        $emailAddresses = $this->createEmailAddresses($this->createStringspector($input));

        $this->assertSame($expected, $emailAddresses->found());
    }

    public static function providesObfuscatedEmailAddresses()
    {
        return array(
            array(
                '*******************',
                'dan@powder-blue.com',
            ),
            array(
                'My email address is *******************.',
                'My email address is dan@powder-blue.com.',
            ),
            array(
                'You can use either ******************* or *******************.',
                'You can use either dan@powder-blue.com or dan@seetheworld.com.',
            ),
        );
    }

    /**
     * @dataProvider providesObfuscatedEmailAddresses
     */
    public function testObfuscateObfuscatesAllEmailAddressesInTheString($expected, $input)
    {
        $stringspector = $this->createStringspector($input);
        $emailAddresses = $this->createEmailAddresses($stringspector);
        $emailAddresses->obfuscate();

        $this->assertSame($expected, $stringspector->getString());
    }

    public function testObfuscateAcceptsAReplacementString()
    {
        $stringspector = $this->createStringspector('Email me at dan@powder-blue.com.');
        $emailAddresses = $this->createEmailAddresses($stringspector);
        $emailAddresses->obfuscate('<span class="redacted email"></span>');

        $this->assertSame('Email me at <span class="redacted email"></span>.', $stringspector->getString());
    }
}
