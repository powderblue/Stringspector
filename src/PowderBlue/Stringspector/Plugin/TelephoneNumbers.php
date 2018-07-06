<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace PowderBlue\Stringspector\Plugin;

use DanBettles\Telex\Telex;
use DanBettles\Telex\NumberFinder;
use DanBettles\Telex\CountryTelephoneNumberMatcherFactory;

/**
 * Manipulates telephone numbers in a string.
 */
class TelephoneNumbers extends AbstractPlugin implements ObfuscatorInterface
{
    /**
     * @param array $matches
     *
     * @return int
     * @todo Rename this?
     */
    private function matchAll(array &$matches = [])
    {
        $telexMatches = (new Telex(new NumberFinder(), new CountryTelephoneNumberMatcherFactory()))
            ->findAll($this->getString())
        ;

        $matches = [];

        foreach ($telexMatches as $match) {
            $matches[] = $match
                ->getCandidate()
                ->getSource()
            ;
        }

        return count($matches);
    }

    /**
     * Returns TRUE if there appears to be a telephone number in the string, or FALSE otherwise.
     *
     * @return bool
     */
    public function found()
    {
        return 0 < $this->matchAll();
    }

    /**
     * As in "obfuscate telephone numbers".
     *
     * @param null|string $replacement
     * @todo Support a closure through `Stringspector::replaceString()`?
     */
    public function obfuscate($replacement = null)
    {
        $matches = [];

        $this->matchAll($matches);

        foreach ($matches as $match) {
            $finalReplacement = $replacement;

            if (null === $replacement) {
                $finalReplacement = SimpleObfuscatorTrait::createObfuscatedString($match);
            }

            $this->replaceString($match, $finalReplacement);
        }
    }
}
