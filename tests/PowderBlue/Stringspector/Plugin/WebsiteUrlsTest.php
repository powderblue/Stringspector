<?php

namespace Tests\PowderBlue\Stringspector\Plugin;

use PowderBlue\Stringspector\Plugin\WebsiteUrls;

class WebsiteUrlsTest extends AbstractPluginTest
{
    /**
     * @return array
     */
    public static function providesStringsContainingWebsiteUrls()
    {
        return [[
            false,
            'Hello, World!',
        ], [
            true,
            'www.seetheworld.com',
        ], [
            true,
            ' www.seetheworld.com ',
        ], [
            true,
            'http://www.seemonaco.com/',
        ], [
            true,
            'https://www.seemallorca.com',
        ], [
            true,
            'seemonaco.com'
        ], [
            true,
            'http://seenice.com'
        ],];
    }

    /**
     * @dataProvider providesStringsContainingWebsiteUrls
     *
     * @param bool   $expected
     * @param string $input
     *
     * @throws \ReflectionException
     */
    public function testFoundReturnsTrueIfThereIsAnWebsiteUrlInTheString($expected, $input)
    {
        $websiteUrls = $this->createPlugin(
            $this->createStringspector($input),
            new WebsiteUrls()
        );

        $this->assertSame($expected, $websiteUrls->found());
    }

    /**
     * @return array
     */
    public static function providesObfuscatedWebsiteUrls()
    {
        return [[
            '*******************',
            'www.seetheworld.com',
        ], [
            'Please check our website: ***********************.',
            'Please check our website: https://seetheworld.com.',
        ], [
            'You can check either *************************** or ***********************.',
            'You can check either https://www.seemallorca.com or https://www.seenice.com.',
        ],];
    }

    /**
     * @dataProvider providesObfuscatedWebsiteUrls
     *
     * @param string $expected
     * @param string $input
     *
     * @throws \ReflectionException
     */
    public function testObfuscateObfuscatesAllWebsiteUrlsInTheString($expected, $input)
    {
        $stringspector = $this->createStringspector($input);

        $websiteUrls = $this->createPlugin($stringspector, new WebsiteUrls());
        $websiteUrls->obfuscate();

        $this->assertSame($expected, $stringspector->getString());
    }

    /**
     * @throws \ReflectionException
     */
    public function testObfuscateAcceptsAReplacementString()
    {
        $stringspector = $this->createStringspector('Please check our website: www.seemallorca.com.');

        $websiteUrls = $this->createPlugin($stringspector, new WebsiteUrls());
        $websiteUrls->obfuscate('<span class="redacted website"></span>');

        $this->assertSame('Please check our website: <span class="redacted website"></span>.', $stringspector->getString());
    }
}
