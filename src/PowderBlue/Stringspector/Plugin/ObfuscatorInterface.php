<?php

namespace PowderBlue\Stringspector\Plugin;

interface ObfuscatorInterface
{
    /**
     * As in "email addresses found?" or "telephone numbers found?".
     *
     * @return bool
     */
    public function found();

    /**
     * @param null|string $replacement
     */
    public function obfuscate($replacement = null);
}
