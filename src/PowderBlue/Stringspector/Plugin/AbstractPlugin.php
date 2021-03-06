<?php
/**
 * @copyright Copyright (c) 2018, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Adrian Dumitrache <adrian.dumitrache@trisoft.ro>
 */

namespace PowderBlue\Stringspector\Plugin;

use PowderBlue\Stringspector\Stringspector;

abstract class AbstractPlugin
{
    /** @var Stringspector */
    private $stringspector;

    /**
     * @param Stringspector $stringspector
     *
     * @return AbstractPlugin
     */
    public function setStringspector(Stringspector $stringspector)
    {
        $this->stringspector = $stringspector;

        return $this;
    }

    /**
     * @return Stringspector
     */
    public function getStringspector()
    {
        return $this->stringspector;
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        return call_user_func_array([$this->getStringspector(), $name], $arguments);
    }

    /**
     * @param string $search
     * @param string $replacement
     *
     * @return AbstractPlugin
     */
    protected function replaceString($search, $replacement)
    {
        $this->setString(str_replace($search, $replacement, $this->getString()));

        return $this;
    }
}
