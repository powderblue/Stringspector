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
     * @return string
     */
    protected function getString()
    {
        return $this
            ->getStringspector()
            ->getString()
        ;
    }
}
