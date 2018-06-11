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
 * @todo Make this a 'complex' obfuscator: implement `ObfuscatorInterface` without the help of `SimpleObfuscatorTrait`?
 * We'll need to employ two different approaches to completely deal with email addresses: we'll need one approach to
 * deal with 'genuine' email addresses; and another to deal with already-disguised (e.g. "dan [at] powder blue com")
 * email addresses.
 */
class EmailAddresses extends AbstractPlugin implements ObfuscatorInterface
{
    use SimpleObfuscatorTrait;

    public function __construct()
    {
        //See http://www.regular-expressions.info/
        $this->setRegExp('/\b([a-zA-Z0-9\.\_\%\+\-]+)(@)([a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,4})\b/');
    }
}
