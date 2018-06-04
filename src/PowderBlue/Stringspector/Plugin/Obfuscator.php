<?php

namespace PowderBlue\Stringspector\Plugin;

class Obfuscator extends AbstractPlugin
{
    /**
     * @param string     $regex
     * @param array|null $matches
     *
     * @return bool
     */
    public function matchAll($regex, array &$matches = null)
    {
        return (bool) preg_match_all($regex, $this->getString(), $matches);
    }

    /**
     * @param string      $regex
     * @param string|null $replacement
     */
    public function obfuscateAll($regex, $replacement = null)
    {
        $matches = [];

        $found = $this->matchAll($regex, $matches);

        if (!$found) {
            return;
        }

        foreach ($matches[0] as $match) {
            $obfuscated = $replacement ?: $this->createDefaultReplacementString(strlen($match));

            $this->replaceWithObfuscated($match, $obfuscated);
        }
    }

    /**
     * @param int    $length
     * @param string $replacementChar
     *
     * @return string
     */
    private function createDefaultReplacementString($length = 1, $replacementChar = '*')
    {
        return str_repeat($replacementChar, $length);
    }

    /**
     * @param string $search
     * @param string $obfuscated
     */
    private function replaceWithObfuscated($search, $obfuscated)
    {
        $string = str_replace($search, $obfuscated, $this->getString());

        $this
            ->getStringspector()
            ->setString($string)
        ;
    }
}
