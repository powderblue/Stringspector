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
     * Creates a `Stringspector` containing the email-address, telephone-number, and website-URL plugins, which can be
     * used to obfuscate contact details in a string.
     *
     * @return Stringspector
     *
     * @throws \ReflectionException
     */
    public function createContactDetailsObfuscator()
    {
        /* @var $stringspector Stringspector */
        $stringspector = (new \ReflectionClass(Stringspector::class))
            ->newInstanceArgs(func_get_args())
        ;

        return $stringspector
            ->setPlugin('emailAddresses', new Plugin\EmailAddresses())
            ->setPlugin('telephoneNumbers', new Plugin\TelephoneNumbers())
            ->setPlugin('websiteUrls', new Plugin\WebsiteUrls())
        ;
    }
}
