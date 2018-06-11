<?php

namespace PowderBlue\Stringspector\Plugin;

/**
 * Manipulates website URLs in a string.
 */
class WebsiteUrls extends AbstractPlugin implements ObfuscatorInterface
{
    use SimpleObfuscatorTrait;

    public function __construct()
    {
        $this->setRegExp('/\b((https?|ftp|smtp):\/\/)?(www.)?[a-z0-9]+\.[a-z]+(\/[a-zA-Z0-9#]+\/?)*\b/');
    }
}
