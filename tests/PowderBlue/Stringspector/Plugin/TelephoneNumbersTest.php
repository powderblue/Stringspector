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
    private function createStringspector()
    {
        $reflectionClass = new \ReflectionClass('PowderBlue\Stringspector\Stringspector');
        return $reflectionClass->newInstanceArgs(func_get_args());
    }

    private function createTelephoneNumbers(Stringspector $stringspector)
    {
        $telephoneNumbers = new TelephoneNumbers();
        $telephoneNumbers->setStringspector($stringspector);
        return $telephoneNumbers;
    }

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
            array(true, '+44 (0) 0000 000 000'),

            array(true, '000 0000 0000'),
            array(true, '0000 000 0000'),
            array(true, '0000 000000'),
            array(true, '0000 0000'),
            array(true, '0000 00 00'),
            array(true, '00000 000000'),
            array(true, '00000 000 000'),

            array(true, 'You can reach me on 07000 000000 or 01000 000000.'),
            array(true, 'You can reach me on (07000) 000000 or (01000) 000000.'),

            array(true, '+44 (0)203 384 1801'),
            array(true, '+44 (0) 203 384 1801'),

            array(true, '+44 (0)1234 012345'),

        //Spain:
            //Old style:
            array(true, '93 412 46 02'),  //(Barcelona)
            array(true, '93.412.46.02'),

            //New style:
            array(true, '917 741 056'),  //(Madrid)
            array(true, '917.741.056'),

        //France:
            array(true, 'Tel : 04.50.55.35.25'),
            array(true, '+33 (0)6 82 89 15 23'),
            array(true, '+33 (0) 476 79 75 10'),
            array(true, '+33(0)476797510'),

            array(true, '+377 98 06 98 99'),
            array(true, ' +37799992550'),
            array(true, '+377 (0)98 06 36 36'),
            array(true, '00377 97 97 90 00'),
        );
    }

    /**
     * @dataProvider providesStringsContainingTelephoneNumbers
     */
    public function testFoundReturnsTrueIfThereAppearsToBeATelephoneNumberInTheString($expected, $input)
    {
        $telephoneNumbers = $this->createTelephoneNumbers($this->createStringspector($input));

        $this->assertSame($expected, $telephoneNumbers->found());
    }

    public static function providesTextContainingTelephoneNumbers()
    {
        return array(
            array(
                '',
                ''
            ),

            array(
                'You can reach me on ************ or ************.',
                'You can reach me on 07000 000000 or 01000 000000.',
            ),
            array(
                'You can reach me on ************** or **************.',
                'You can reach me on (07000) 000000 or (01000) 000000.',
            ),
            array(
                'You can reach me on +******************.',
                'You can reach me on +44 (0)0000 000 000.',
            ),
            array(
                'You can reach me on *************.',
                'You can reach me on 00000 000 000.',
            ),

            array(
                '+*************.  Start.',
                '+33 693+633333.  Start.',
            ),
            array(
                'End.  +*************',
                'End.  +33 693+633333',
            ),
            array(
                'Call me +*************/+*************',
                'Call me +33 693+633333/+33 789 456123',
            ),
            array(
                'In the +************* middle.',
                'In the +33 693+633333 middle.',
            ),

            array(
                '+***************.  Start.',
                '+377 92 05 00 50.  Start.',
            ),
            array(
                'End.  +***************',
                'End.  +377 92 05 00 50',
            ),
            array(
                'Call me +***************/+*****************',
                'Call me +377 92 05 00 50/+377 (0)98 062 121',
            ),
            array(
                'In the +*************** middle.',
                'In the +377 92 05 00 50 middle.',
            ),
            array(
                'Instead of using this chat, please call me on +*****************.',
                'Instead of using this chat, please call me on +44 (0)1234 012345.',
            ),
        );
    }

    /**
     * @dataProvider providesTextContainingTelephoneNumbers
     */
    public function testObfuscateObfuscatesAllTelephoneNumbersInTheString($expected, $input)
    {
        $stringspector = $this->createStringspector($input);
        $telephoneNumbers = $this->createTelephoneNumbers($stringspector);
        $telephoneNumbers->obfuscate();

        $this->assertSame($expected, $stringspector->getString());
    }

    public function testObfuscateAcceptsAReplacementString()
    {
        $stringspector = $this->createStringspector('Call me on 01234 012345.');
        $telephoneNumbers = $this->createTelephoneNumbers($stringspector);
        $telephoneNumbers->obfuscate('<span class="redacted tel"></span>');

        $this->assertSame('Call me on <span class="redacted tel"></span>.', $stringspector->getString());
    }

    public static function providesTelephoneNumbersFromDatabaseExports()
    {
        $fixturesDir = __DIR__ . '/TelephoneNumbersTest';

        $testMethodArgsRecords = array();

        foreach (glob("{$fixturesDir}/*.csv") as $exportFilePath) {
            $exportFileLines = file($exportFilePath);

            foreach ($exportFileLines as $exportFileLine) {
                $testMethodArgsRecords[] = array($exportFileLine);
            }
        }

        return $testMethodArgsRecords;
    }

    /**
     * @dataProvider providesTelephoneNumbersFromDatabaseExports
     */
    public function testFoundReturnsTrueForEachLineInAFile($input)
    {
        $telephoneNumbers = $this->createTelephoneNumbers($this->createStringspector($input));

        $this->assertTrue($telephoneNumbers->found());
    }
}
