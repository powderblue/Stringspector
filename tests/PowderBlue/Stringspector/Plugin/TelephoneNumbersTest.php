<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace Tests\PowderBlue\Stringspector\Plugin;

use PowderBlue\Stringspector\Plugin\TelephoneNumbers;

class TelephoneNumbersTest extends AbstractAbstractPluginTest
{
    const PHONE_NUMBERS_GITHUB_CSV_FILE = 'https://raw.githubusercontent.com/danbettles/telex/v0.0.0-alpha.0/tests/DanBettles/Telex/TelexTest/telephone_numbers.csv';

    /** @var array */
    private static $csvFiles = [
        self::PHONE_NUMBERS_GITHUB_CSV_FILE,
    ];

    /**
     * @return array
     */
    public static function providesStringsContainingTelephoneNumbers()
    {
        return [
            [false, '',],

            //UK:
            [true, '(020) 0123 0123',], //(London)
            [true, '(0113) 012 0123',], //(Leeds)
            [true, '(00000) 00000',],
            [true, '(01234) 012345',],
            [true, '(016977) 0123',], //(Brampton)
            [true, '(015396) 01234',], //(Sedbergh)
            [true, '+44 (0)0000 000 000',],
            [true, '+44 (0) 0000 000 000',],

            [true, '000 0000 0000',],
            [true, '0000 000 0000',],
            [true, '0000 000000',],
            [true, '0000 0000',],
            [true, '0000 00 00',],
            [true, '00000 000000',],
            [true, '00000 000 000',],

            [true, 'You can reach me on 07000 000000 or 01000 000000.',],
            [true, 'You can reach me on (07000) 000000 or (01000) 000000.',],

            [true, '+44 (0)203 384 1801',],
            [true, '+44 (0) 203 384 1801',],

            [true, '+44 (0)1234 012345',],

            //Spain:
            //Old style:
            [true, '93 412 46 02',], //(Barcelona)
            [true, '93.412.46.02',],
            //New style:
            [true, '917 741 056',], //(Madrid)
            [true, '917.741.056',],

            //France:
            [true, 'Tel : 04.50.55.35.25',],
            [true, '+33 (0)6 82 89 15 23',],
            [true, '+33 (0) 476 79 75 10',],
            [true, '+33(0)476797510',],

            [true, '+377 98 06 98 99',],
            [true, ' +37799992550',],
            [true, '+377 (0)98 06 36 36',],
            [true, '00377 97 97 90 00',],

            [true, '0033.450.79.09.66',],
            [true, '50.79.09.66',],
        ];
    }

    /**
     * @dataProvider providesStringsContainingTelephoneNumbers
     *
     * @param bool   $expected
     * @param string $string
     *
     * @throws \ReflectionException
     */
    public function testFoundReturnsTrueIfThereAppearsToBeATelephoneNumberInTheString($expected, $string)
    {
        $telephoneNumbers = $this->createPlugin(
            $this->createStringspector($string),
            new TelephoneNumbers()
        );

        $this->assertSame($expected, $telephoneNumbers->found());
    }

    /**
     * @return array
     */
    public static function providesTextContainingTelephoneNumbers()
    {
        return [[
            '',
            '',
        ], [
            'You can reach me on ************ or ************.',
            'You can reach me on 07000 000000 or 01000 000000.',
        ], [
            'You can reach me on ************** or **************.',
            'You can reach me on (07000) 000000 or (01000) 000000.',
        ], [
            'You can reach me on +******************.',
            'You can reach me on +44 (0)0000 000 000.',
        ], [
            'You can reach me on *************.',
            'You can reach me on 00000 000 000.',
        ], [
            '+*************.  Start.',
            '+33 693+633333.  Start.',
        ], [
            'End.  +*************',
            'End.  +33 693+633333',
        ], [
            'Call me +*************/+*************',
            'Call me +33 693+633333/+33 789 456123',
        ], [
            'In the +************* middle.',
            'In the +33 693+633333 middle.',
        ], [
            '+***************.  Start.',
            '+377 92 05 00 50.  Start.',
        ], [
            'End.  +***************',
            'End.  +377 92 05 00 50',
        ], [
            'Call me +***************/+*****************',
            'Call me +377 92 05 00 50/+377 (0)98 062 121',
        ], [
            'In the +*************** middle.',
            'In the +377 92 05 00 50 middle.',
        ], [
            'Instead of using this chat, please call me on +*****************.',
            'Instead of using this chat, please call me on +44 (0)1234 012345.',
        ],];
    }

    /**
     * @dataProvider providesTextContainingTelephoneNumbers
     *
     * @param string $expected
     * @param string $string
     *
     * @throws \ReflectionException
     */
    public function testObfuscateObfuscatesAllTelephoneNumbersInTheString($expected, $string)
    {
        $stringspector = $this->createStringspector($string);

        $telephoneNumbers = $this->createPlugin($stringspector, new TelephoneNumbers());
        $telephoneNumbers->obfuscate();

        $this->assertSame($expected, $stringspector->getString());
    }

    /**
     * @throws \ReflectionException
     */
    public function testObfuscateAcceptsAReplacementString()
    {
        $stringspector = $this->createStringspector('Call me on 01234 012345.');

        $telephoneNumbers = $this->createPlugin($stringspector, new TelephoneNumbers());
        $telephoneNumbers->obfuscate('<span class="redacted tel"></span>');

        $this->assertSame('Call me on <span class="redacted tel"></span>.', $stringspector->getString());
    }

    /**
     * @return array
     */
    public static function providesTelephoneNumbersFromCsvFiles()
    {
        $rows = [];

        foreach (self::$csvFiles as $csvFile) {
            $data = file_get_contents($csvFile);

            $rows[] = explode("\n", $data);
        }

        return $rows;
    }

    /**
     * @dataProvider providesTelephoneNumbersFromCsvFiles
     *
     * @param string $string
     *
     * @throws \ReflectionException
     */
    public function testFoundReturnsTrueForEachLineInAFile($string)
    {
        $telephoneNumbers = $this->createPlugin(
            $this->createStringspector($string),
            new TelephoneNumbers()
        );

        $this->assertTrue($telephoneNumbers->found());
    }
}
