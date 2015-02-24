<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace PowderBlue\Stringspector;

class Factory
{
    public function create()
    {
        $reflectionClass = new \ReflectionClass('PowderBlue\Stringspector\Stringspector');
        $stringspector = $reflectionClass->newInstanceArgs(func_get_args());

        $stringspector->setPlugin('emailAddresses', new Plugin\EmailAddresses());
        $stringspector->setPlugin('telephoneNumbers', new Plugin\TelephoneNumbers());

        return $stringspector;
    }
}
