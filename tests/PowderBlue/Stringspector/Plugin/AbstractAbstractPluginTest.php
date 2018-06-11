<?php

namespace Tests\PowderBlue\Stringspector\Plugin;

use PowderBlue\Stringspector\Stringspector;
use PowderBlue\Stringspector\Plugin\AbstractPlugin;

abstract class AbstractAbstractPluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Stringspector
     *
     * @throws \ReflectionException
     */
    protected function createStringspector()
    {
        $reflectionClass = new \ReflectionClass(Stringspector::class);

        /* @var $stringspector Stringspector */
        $stringspector = $reflectionClass->newInstanceArgs(func_get_args());

        return $stringspector;
    }

    /**
     * @param Stringspector  $stringspector
     * @param AbstractPlugin $plugin
     *
     * @return AbstractPlugin
     *
     * @throws \ReflectionException
     */
    protected function createPlugin(Stringspector $stringspector, AbstractPlugin $plugin)
    {
        return $plugin->setStringspector($stringspector);
    }
}
