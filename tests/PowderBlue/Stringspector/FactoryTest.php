<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace Tests\PowderBlue\Stringspector\Factory;

use PowderBlue\Stringspector\Factory;

class Test extends \PHPUnit_Framework_TestCase
{
    public function testCreateCreatesAFullyLoadedStringspector()
    {
        $factory1 = new Factory();
        $stringspector1 = $factory1->create();

        $this->assertInstanceOf('PowderBlue\Stringspector\Stringspector', $stringspector1);
        $this->assertSame('', $stringspector1->getString());
        $this->assertInstanceOf('PowderBlue\Stringspector\Plugin\EmailAddresses', $stringspector1->emailAddresses);
        $this->assertInstanceOf('PowderBlue\Stringspector\Plugin\TelephoneNumbers', $stringspector1->telephoneNumbers);

        $factory2 = new Factory();
        $stringspector2 = $factory2->create('Hello, World!');

        $this->assertInstanceOf('PowderBlue\Stringspector\Stringspector', $stringspector2);
        $this->assertSame('Hello, World!', $stringspector2->getString());
        $this->assertInstanceOf('PowderBlue\Stringspector\Plugin\EmailAddresses', $stringspector2->emailAddresses);
        $this->assertInstanceOf('PowderBlue\Stringspector\Plugin\TelephoneNumbers', $stringspector2->telephoneNumbers);
    }
}
