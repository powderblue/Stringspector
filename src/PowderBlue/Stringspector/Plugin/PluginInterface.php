<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace PowderBlue\Stringspector\Plugin;

use PowderBlue\Stringspector\Stringspector;

interface PluginInterface
{
    /**
     * @param Stringspector $stringspector
     */
    public function setStringspector(Stringspector $stringspector);
}
