<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace Tests\PowderBlue\Stringspector;

use PowderBlue\Stringspector\Stringspector;
use PowderBlue\Stringspector\Factory;
use PowderBlue\Stringspector\Plugin;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatecontactdetailsobfuscatorCreatesAPreconfiguredStringspector()
    {
        $stringspector1 = (new Factory())
            ->createContactDetailsObfuscator()
        ;

        $this->assertInstanceOf(Stringspector::class, $stringspector1);
        $this->assertSame('', $stringspector1->getString());
        $this->assertInstanceOf(Plugin\EmailAddresses::class, $stringspector1->emailAddresses);
        $this->assertInstanceOf(Plugin\TelephoneNumbers::class, $stringspector1->telephoneNumbers);
        $this->assertInstanceOf(Plugin\WebsiteUrls::class, $stringspector1->websiteUrls);

        $stringspector2 = (new Factory())
            ->createContactDetailsObfuscator('Hello, World!')
        ;

        $this->assertInstanceOf(Stringspector::class, $stringspector2);
        $this->assertSame('Hello, World!', $stringspector2->getString());
        $this->assertInstanceOf(Plugin\EmailAddresses::class, $stringspector2->emailAddresses);
        $this->assertInstanceOf(Plugin\TelephoneNumbers::class, $stringspector2->telephoneNumbers);
        $this->assertInstanceOf(Plugin\WebsiteUrls::class, $stringspector2->websiteUrls);
    }
}
