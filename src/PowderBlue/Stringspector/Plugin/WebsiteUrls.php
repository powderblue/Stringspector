<?php

namespace PowderBlue\Stringspector\Plugin;

class WebsiteUrls extends AbstractPlugin
{
    const WEBSITE_URL_REGEXP = '/\b((https?|ftp|smtp):\/\/)?(www.)?[a-z0-9]+\.[a-z]+(\/[a-zA-Z0-9#]+\/?)*\b/';

    /**
     * @return bool
     */
    public function found()
    {
        /* @var $obfuscatorPlugin Obfuscator */
        $obfuscatorPlugin = $this
            ->getStringspector()
            ->getPlugin('obfuscator')
        ;

        return $obfuscatorPlugin->matchAll(self::WEBSITE_URL_REGEXP);
    }

    /**
     * @param string|null $replacement
     */
    public function obfuscate($replacement = null)
    {
        /* @var $obfuscatorPlugin Obfuscator */
        $obfuscatorPlugin = $this
            ->getStringspector()
            ->getPlugin('obfuscator')
        ;

        $obfuscatorPlugin->obfuscateAll(self::WEBSITE_URL_REGEXP, $replacement);
    }
}
