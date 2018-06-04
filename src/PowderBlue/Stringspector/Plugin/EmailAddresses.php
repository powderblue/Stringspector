<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace PowderBlue\Stringspector\Plugin;

/**
 * Manipulates email addresses in a string.
 *
 * @todo Look for, and deal with, disguised email addresses.
 */
class EmailAddresses extends AbstractPlugin
{
    /**
     * Regular expression that will match a genuine email address in a string.
     *
     * @url http://www.regular-expressions.info/
     * @var string
     */
    const REG_EXP = '/\b([a-zA-Z0-9\.\_\%\+\-]+)(@)([a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,4})\b/';

    /**
     * Returns TRUE if there is an email address in the string, or FALSE otherwise.
     *
     * @return bool
     */
    public function found()
    {
        /* @var $obfuscatorPlugin Obfuscator */
        $obfuscatorPlugin = $this
            ->getStringspector()
            ->getPlugin('obfuscator')
        ;

        return $obfuscatorPlugin->matchAll(self::REG_EXP);
    }

    /**
     * Obfuscates all email addresses in the string.
     *
     * @param string|null $replacement
     */
    public function obfuscate($replacement = null)
    {
        /* @var $obfuscatorPlugin Obfuscator */
        $obfuscatorPlugin = $this
            ->getStringspector()
            ->getPlugin('obfuscator')
        ;

        $obfuscatorPlugin->obfuscateAll(self::REG_EXP, $replacement);
    }
}
