<?php

namespace Tests\PowderBlue\Stringspector\Plugin;

use PowderBlue\Stringspector\Stringspector;
use PowderBlue\Stringspector\Plugin\AbstractPlugin;
use PowderBlue\Stringspector\Plugin\Obfuscator;

abstract class AbstractPluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws \ReflectionException
     *
     * @return Stringspector
     */
    protected function createStringspector()
    {
        $reflectionClass = new \ReflectionClass(Stringspector::class);

        /* @var $stringspector Stringspector */
        $stringspector = $reflectionClass->newInstanceArgs(func_get_args());

        return $stringspector->setPlugin('obfuscator', new Obfuscator());
    }

    /**
     * @param Stringspector  $stringspector
     * @param AbstractPlugin $plugin
     *
     * @throws \ReflectionException
     *
     * @return AbstractPlugin
     */
    protected function createPlugin(Stringspector $stringspector, AbstractPlugin $plugin)
    {
        return $plugin->setStringspector($stringspector);
    }
}
