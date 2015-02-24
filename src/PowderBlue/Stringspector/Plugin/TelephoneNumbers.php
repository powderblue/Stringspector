<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace PowderBlue\Stringspector\Plugin;

use PowderBlue\Stringspector\Stringspector;

/**
 * Manipulates telephone numbers in a string.
 * 
 * @todo Look for, and deal with, disguised telephone numbers?
 * @todo 'Compile' a single regular expression for each country?  Will that improve performance?
 */
class TelephoneNumbers implements PluginInterface
{
    /**
     * @url http://en.wikipedia.org/wiki/Telephone_numbers_in_Spain
     * @url http://www.tripadvisor.co.uk/Travel-g187427-s605/Spain:Telephones.html
     * @url http://en.wikipedia.org/wiki/Telephone_numbers_in_the_United_Kingdom
     * @var array
     */
    private static $countryRegExps = array(
        'es' => array(
            '/\b\d{2}\s+\d{3}\s+\d{2}\s+\d{2}\b/',  //Old style (2-3-2-2), space-separated
            '/\b\d{2}\.\d{3}\.\d{2}\.\d{2}\b/',  //Old style (2-3-2-2), dot-separated
            '/\b\d{3}\s+\d{3}\s+\d{3}\b/',  //New style (3-3-3), space-separated
            '/\b\d{3}\.\d{3}\.\d{3}\b/',  //New style (3-3-3), dot-separated
        ),
        'gb' => array(
            '/\(\d{6}\)\s+\d{4,5}\b/',  //(000000) 0000[0]
            '/\(\d{5}\)\s+\d{5,6}\b/',  //(00000) 00000[0]
            '/\(\d{4}\)\s+\d{3}\s+\d{4}\b/',  //(0000) 000 0000
            '/\(\d\)\d{4}\s+\d{3}\s+\d{3}\b/',  //(0)0000 000 000
            '/\b\d{4}\s+\d{3}\s+\d{4}\b/',  //0000 000 0000
            '/\b\d{3}\s+\d{4}\s+\d{4}\b/',  //000 0000 0000
            '/\b\d{5}\s+\d{6}\b/',  //00000 000000
            '/\b\d{5}\s+\d{3}\s+\d{3}\b/',  //00000 000 000
            '/\b\d{4}\s+(\d{4}|\d{6})\b/',  //0000 0000[00]
            '/\b\d{4}\s+\d{2}\s+\d{2}\b/',  //0000 00 00
        ),
    );

    /**
     * @var PowderBlue\Stringspector\Stringspector
     */
    private $stringspector;

    /**
     * @param PowderBlue\Stringspector\Stringspector $stringspector
     * @return void
     */
    public function setStringspector(Stringspector $stringspector)
    {
        $this->stringspector = $stringspector;
    }

    /**
     * @return PowderBlue\Stringspector\Stringspector
     */
    private function getStringspector()
    {
        return $this->stringspector;
    }

    /**
     * Returns TRUE if there appears to be a telephone number in the string, or FALSE otherwise.
     * 
     * @return boolean
     */
    public function found()
    {
        $string = $this->getStringspector()->getString();

        foreach (self::$countryRegExps as $regExps) {
            foreach ($regExps as $regExp) {
                if (preg_match($regExp, $string)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return void
     */
    public function obfuscate()
    {
        $string = $this->getStringspector()->getString();

        foreach (self::$countryRegExps as $regExps) {
            foreach ($regExps as $regExp) {
                $telephoneNumberMatches = array();
                $telephoneNumberFound = (bool) preg_match_all($regExp, $string, $telephoneNumberMatches);

                if (!$telephoneNumberFound) {
                    continue;
                }

                foreach ($telephoneNumberMatches[0] as $telephoneNumber) {
                    $string = str_replace(
                        $telephoneNumber,
                        str_repeat('*', strlen($telephoneNumber)),
                        $string
                    );
                }
            }
        }

        $this->getStringspector()->setString($string);
    }
}
