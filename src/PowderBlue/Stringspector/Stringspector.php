<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace PowderBlue\Stringspector;

use PowderBlue\Stringspector\Plugin\PluginInterface;

class Stringspector
{
    /**
     * @var string
     */
    private $string;

    /**
     * @var PowderBlue\Stringspector\Plugin\PluginInterface[]
     */
    private $plugins = array();

    /**
     * @param string [$string = '']
     * @return void
     */
    public function __construct($string = '')
    {
        $this->setString($string);
    }

    /**
     * @param string $string
     * @return void
     */
    public function setString($string)
    {
        $this->string = $string;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function hasPlugin($name)
    {
        return array_key_exists($name, $this->plugins);
    }

    /**
     * @param string $name
     * @return PowderBlue\Stringspector\Plugin\PluginInterface
     * @throws \OutOfBoundsException If the plugin with the specified name does not exist.
     */
    public function getPlugin($name)
    {
        if (!$this->hasPlugin($name)) {
            throw new \OutOfBoundsException("The plugin \"{$name}\" does not exist.");
        }

        return $this->plugins[$name];
    }

    /**
     * @param string $name
     * @param PowderBlue\Stringspector\Plugin\PluginInterface $plugin
     * @return void
     */
    public function setPlugin($name, PluginInterface $plugin)
    {
        $this->plugins[$name] = $plugin;
        $this->getPlugin($name)->setStringspector($this);
    }

    /**
     * @param string $name
     * @return PowderBlue\Stringspector\Plugin\PluginInterface
     */
    public function __get($name)
    {
        return $this->getPlugin($name);
    }
}
