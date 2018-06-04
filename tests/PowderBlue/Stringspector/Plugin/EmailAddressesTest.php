<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace Tests\PowderBlue\Stringspector\Plugin;

use PowderBlue\Stringspector\Plugin\EmailAddresses;

class EmailAddressesTest extends AbstractPluginTest
{
    /**
     * @return array
     */
    public static function providesStringsContainingEmailAddresses()
    {
        return [[
            false,
            'Hello, World!',
        ], [
            true,
            'dan@powder-blue.com',
        ], [
            true,
            ' dan@powder-blue.com ',
        ], [
            true,
            'My email address is dan@powder-blue.com.',
        ], [
            true,
            'You can use either dan@powder-blue.com or dan@seetheworld.com.',
        ],];
    }

    /**
     * @dataProvider providesStringsContainingEmailAddresses
     *
     * @param bool   $expected
     * @param string $input
     *
     * @throws \ReflectionException
     */
    public function testFoundReturnsTrueIfThereIsAnEmailAddressInTheString($expected, $input)
    {
        $emailAddresses = $this->createPlugin(
            $this->createStringspector($input),
            new EmailAddresses()
        );

        $this->assertSame($expected, $emailAddresses->found());
    }

    /**
     * @return array
     */
    public static function providesObfuscatedEmailAddresses()
    {
        return [[
            '*******************',
            'dan@powder-blue.com',
        ], [
            'My email address is *******************.',
            'My email address is dan@powder-blue.com.',
        ], [
            'You can use either ******************* or *******************.',
            'You can use either dan@powder-blue.com or dan@seetheworld.com.',
        ],];
    }

    /**
     * @dataProvider providesObfuscatedEmailAddresses
     *
     * @param string $expected
     * @param string $input
     *
     * @throws \ReflectionException
     */
    public function testObfuscateObfuscatesAllEmailAddressesInTheString($expected, $input)
    {
        $stringspector = $this->createStringspector($input);

        $emailAddresses = $this->createPlugin($stringspector, new EmailAddresses());
        $emailAddresses->obfuscate();

        $this->assertSame($expected, $stringspector->getString());
    }

    /**
     * @throws \ReflectionException
     */
    public function testObfuscateAcceptsAReplacementString()
    {
        $stringspector = $this->createStringspector('Email me at dan@powder-blue.com.');

        $emailAddresses = $this->createPlugin($stringspector, new EmailAddresses());
        $emailAddresses->obfuscate('<span class="redacted email"></span>');

        $this->assertSame('Email me at <span class="redacted email"></span>.', $stringspector->getString());
    }
}
