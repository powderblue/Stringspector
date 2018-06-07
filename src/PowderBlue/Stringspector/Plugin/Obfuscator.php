<?php

namespace PowderBlue\Stringspector\Plugin;

class Obfuscator extends AbstractPlugin
{
    /**
     * @param string $regex
     *
     * @return array
     */
    public function matchAll($regex)
    {
        $matches = [];

        preg_match_all($regex, $this->getString(), $matches);

        return reset($matches);
    }

    /**
     * @param string      $regex
     * @param string|null $userReplace
     */
    public function obfuscateAll($regex, $userReplace = null)
    {
        $matches = $this->matchAll($regex);

        if (empty($matches)) {
            return;
        }

        foreach ($matches as $match) {
            $finalReplace = null === $userReplace
                ? $this->createDefaultReplacementString(strlen($match))
                : $userReplace
            ;

            $this
                ->getStringspector()
                ->replaceString($match, $finalReplace)
            ;
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
}
