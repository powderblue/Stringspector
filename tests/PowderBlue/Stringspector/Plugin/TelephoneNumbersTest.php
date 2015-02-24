<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace Tests\PowderBlue\Stringspector\Plugin\TelephoneNumbers;

use PowderBlue\Stringspector\Plugin\TelephoneNumbers;
use PowderBlue\Stringspector\Stringspector;

class Test extends \PHPUnit_Framework_TestCase
{
    public function testImplementsThePluginInterface()
    {
        $reflectionClass = new \ReflectionClass('PowderBlue\Stringspector\Plugin\TelephoneNumbers');
        $this->assertTrue($reflectionClass->implementsInterface('PowderBlue\Stringspector\Plugin\PluginInterface'));
    }

    public static function providesStringsContainingTelephoneNumbers()
    {
        return array(
            array(false, ''),
        //UK:
            array(true, '(020) 0123 0123'),  //(London)
            array(true, '(0113) 012 0123'),  //(Leeds)
            array(true, '(00000) 00000'),
            array(true, '(01234) 012345'),
            array(true, '(016977) 0123'),  //(Brampton)
            array(true, '(015396) 01234'),  //(Sedbergh)
            array(true, '+44 (0)0000 000 000'),

            array(true, '000 0000 0000'),
            array(true, '0000 000 0000'),
            array(true, '0000 000000'),
            array(true, '0000 0000'),
            array(true, '0000 00 00'),
            array(true, '00000 000000'),
            array(true, '00000 000 000'),

            array(true, 'You can reach me on 07000 000000 or 01000 000000.'),
            array(true, 'You can reach me on (07000) 000000 or (01000) 000000.'),
        //Spain:
            //Old style:
            array(true, '93 412 46 02'),  //(Barcelona)
            array(true, '93.412.46.02'),
            //New style:
            array(true, '917 741 056'),  //(Madrid)
            array(true, '917.741.056'),
        );
    }

    /**
     * @dataProvider providesStringsContainingTelephoneNumbers
     */
    public function testFoundReturnsTrueIfThereAppearsToBeATelephoneNumberInTheString($expected, $input)
    {
        $stringspector = new Stringspector($input);

        $telephoneNumbers = new TelephoneNumbers();
        $telephoneNumbers->setStringspector($stringspector);

        $this->assertSame($expected, $telephoneNumbers->found());
    }

    public static function providesSomething()
    {
        return array(
            array('', ''),
            array(
                'You can reach me on ************ or ************.',
                'You can reach me on 07000 000000 or 01000 000000.',
            ),
            array(
                'You can reach me on ************** or **************.',
                'You can reach me on (07000) 000000 or (01000) 000000.',
            ),
            array(
                'You can reach me on +44 ***************.',
                'You can reach me on +44 (0)0000 000 000.',
            ),
            array(
                'You can reach me on *************.',
                'You can reach me on 00000 000 000.',
            ),
        );
    }

    /**
     * @dataProvider providesSomething
     */
    public function testObfuscateObfuscatesAllTelephoneNumbersInTheString($expected, $input)
    {
        $stringspector = new Stringspector($input);

        $telephoneNumbers = new TelephoneNumbers();
        $telephoneNumbers->setStringspector($stringspector);
        $telephoneNumbers->obfuscate();

        $this->assertSame($expected, $stringspector->getString());
    }
}
