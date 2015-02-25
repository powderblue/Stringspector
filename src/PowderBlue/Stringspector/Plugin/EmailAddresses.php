<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace PowderBlue\Stringspector\Plugin;

use PowderBlue\Stringspector\Stringspector;

/**
 * Manipulates email addresses in a string.
 * 
 * @todo Look for, and deal with, disguised email addresses.
 */
class EmailAddresses implements PluginInterface
{
    /**
     * Regular expression that will match a genuine email address in a string.
     * 
     * @url http://www.regular-expressions.info/
     * @var string
     */
    const REG_EXP = '/\b([a-zA-Z0-9\.\_\%\+\-]+)(@)([a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,4})\b/';

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
     * Returns TRUE if there is an email address in the string, or FALSE otherwise.
     * 
     * @return int
     */
    public function found()
    {
        return (bool) preg_match_all(self::REG_EXP, $this->getStringspector()->getString());
    }

    /**
     * Obfuscates all email addresses in the string.
     * 
     * @param mixed [$replacement]
     * @return void
     */
    public function obfuscate()
    {
        $string = $this->getStringspector()->getString();

        $emailAddressMatches = array();
        $emailAddressFound = (bool) preg_match_all(self::REG_EXP, $string, $emailAddressMatches);

        if (!$emailAddressFound) {
            return;
        }

        foreach ($emailAddressMatches[0] as $emailAddress) {
            $obfuscatedEmailAddress = func_num_args() ? func_get_arg(0) : str_repeat('*', strlen($emailAddress));
            $string = str_replace($emailAddress, $obfuscatedEmailAddress, $string);
        }

        $this->getStringspector()->setString($string);
    }
}
