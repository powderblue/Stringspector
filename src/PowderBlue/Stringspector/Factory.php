<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace PowderBlue\Stringspector;

class Factory
{
    /**
     * @return Stringspector
     *
     * @throws \ReflectionException
     */
    public function create()
    {
        $reflectionClass = new \ReflectionClass(Stringspector::class);

        /* @var $stringspector Stringspector */
        $stringspector = $reflectionClass->newInstanceArgs(func_get_args());

        return $stringspector
            ->setPlugin('obfuscator', new Plugin\Obfuscator())
            ->setPlugin('emailAddresses', new Plugin\EmailAddresses())
            ->setPlugin('telephoneNumbers', new Plugin\TelephoneNumbers())
            ->setPlugin('websiteUrls', new Plugin\WebsiteUrls())
        ;
    }
}
