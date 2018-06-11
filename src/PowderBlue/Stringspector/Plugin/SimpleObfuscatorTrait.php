<?php

namespace PowderBlue\Stringspector\Plugin;

trait SimpleObfuscatorTrait
{
    /** @var string */
    private $regExp;

    /**
     * @param string $regExp
     * @return $this
     */
    protected function setRegExp($regExp)
    {
        $this->regExp = $regExp;
        return $this;
    }

    /**
     * @return string
     */
    protected function getRegExp()
    {
        return $this->regExp;
    }

    /**
     * As in "match all email addresses" or "match all telephone numbers".
     *
     * @param array $matches
     *
     * @return int
     *
     * @todo Rename this, so that it's apparent purpose is more closely linked with obfuscating.
     */
    protected function matchAll(array &$matches = [])
    {
        return preg_match_all($this->getRegExp(), $this->getString(), $matches);
    }

    /**
     * As in "email addresses found?" or "telephone numbers found?".
     *
     * @return bool
     */
    public function found()
    {
        return 0 < $this->matchAll();
    }

    /**
     * As in "obfuscate email addresses" or "obfuscate telephone numbers".
     *
     * @param null|string $replacement
     * @todo Support a closure through `Stringspector::replaceString()`?
     */
    public function obfuscate($replacement = null)
    {
        $matches = [];
        $this->matchAll($matches);

        foreach ($matches[0] as $match) {
            $finalReplacement = $replacement;

            if (null === $replacement) {
                $finalReplacement = self::createObfuscatedString($match);
            }

            $this->replaceString($match, $finalReplacement);
        }
    }

    /**
     * @param string $string
     * @return string
     * @todo Rename this?
     */
    public static function createObfuscatedString($string)
    {
        return str_repeat('*', strlen($string));
    }
}
