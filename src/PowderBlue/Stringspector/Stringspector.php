<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace PowderBlue\Stringspector;

use PowderBlue\Stringspector\Plugin\AbstractPlugin;

class Stringspector
{
    /** @var string */
    private $string;

    /** @var AbstractPlugin[] */
    private $plugins = [];

    /**
     * @param string $string
     */
    public function __construct($string = '')
    {
        $this->setString($string);
    }

    /**
     * @param string $string
     *
     * @return Stringspector
     */
    public function setString($string)
    {
        $this->string = $string;

        return $this;
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
     *
     * @return bool
     */
    private function hasPlugin($name)
    {
        return array_key_exists($name, $this->plugins);
    }

    /**
     * @param string $name
     *
     * @return AbstractPlugin
     *
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
     * @param string         $name
     * @param AbstractPlugin $plugin
     *
     * @return Stringspector
     */
    public function setPlugin($name, AbstractPlugin $plugin)
    {
        $this->plugins[$name] = $plugin;
        $this
            ->getPlugin($name)
            ->setStringspector($this)
        ;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return AbstractPlugin
     */
    public function __get($name)
    {
        return $this->getPlugin($name);
    }

    /**
     * @param string $search
     * @param string $replacement
     *
     * @return Stringspector
     */
    public function replaceString($search, $replacement)
    {
        return $this->setString(str_replace($search, $replacement, $this->getString()));
    }
}
