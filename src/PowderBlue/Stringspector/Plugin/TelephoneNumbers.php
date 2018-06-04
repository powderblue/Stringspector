<?php
/**
 * @copyright Copyright (c) 2015, Powder Blue
 * @license http://www.opensource.org/licenses/MIT MIT
 * @author Dan Bettles <dan@powder-blue.com>
 */

namespace PowderBlue\Stringspector\Plugin;

/**
 * Manipulates telephone numbers in a string.
 *
 * @todo Look for, and deal with, disguised telephone numbers?
 * @todo At startup, 'compile' a single regular expression for each country?  Will that improve performance?
 */
class TelephoneNumbers extends AbstractPlugin
{
    /**
     * @var array
     * @todo Continue grouping, and merging, patterns.
     * @todo Try to phase out formats excluding a country code - sometimes these will pick-up parts of international
     * numbers!
     * @todo Use zeroes to help reduce ambiguity.
     */
    private static $countryRegExps = [
        '*' => [
        //Including country code:
            '/\+(44)\s+\d{11}\b/',                                              //+dd ddddddddddd
            '/\+(44)\s+\d{10}\b/',                                              //+dd dddddddddd
            '/\+(351|353)\s+\d{9}\b/',                                          //+ddd ddddddddd
            '/\+(372|852)\s+\d{8}\b/',                                          //+ddd dddddddd

            '/\+(33|49|61|37\d)\d{7}\b/',                                       //+dd[d]ddddddd
            '/\+(45|47|35\d|37\d|85\d|216)\d{8}\b/',                            //+dd[d]dddddddd
            '/\+(7|27|31|32|33|34|41|44|46|61|25\d|35\d|38\d|96\d|97\d)\d{9}\b/',   //+dd[d]ddddddddd
            '/\+(7|44|49|25\d)\d{10}\b/',                                       //+dd[d]dddddddddd
            '/\+(49|55)\d{11}\b/',                                              //+dd[d]ddddddddddd

            '/\b00\s*(96\d)\d{7}\b/',                                           //00 dd[d]ddddddd
            '/\b00\s*(35\d|45)\d{8}\b/',                                        //00 dd[d]dddddddd
            '/\b00\s*(31|32|33|34|35\d|40|41|46|61)\s*\d{9}\b/',                //00 dd[d] ddddddddd
            '/\b00\s*(7|30|33|35\d|44|49|90)\s*\d{10}\b/',                      //00 dd[d] dddddddddd
            '/\b00\s*(44)\s*\d{11}\b/',                                         //00 dd[d] ddddddddddd

            '/\(\+45\)\d{8}\b/',                                                //(+dd)dddddddd

            '/(\(\+?\b\d{2}\)\s+)?(\d{3}\s+){2}\d{4}\b/',                       //dd ddd ddd dddd.  Also matches similar local format.  Moved up from "ch".
            '/\b(\d{2}\s+){5}\d{2}\b/',                                         //dd dd dd dd dd dd
            '/\b\d{2}\s+\(\d\)\s+\d{4}\s+\d{5}\b/',                             //dd (d) dddd ddddd
            '/\b00\d{3}\s+(\d{2}\s+){2}\d{2}\b/',                               //00ddd dd dd dd.  Possibly "Asia-Pacific" format.
        ],
        'es' => [
        //Including country code:
            '/\b\d{2}\s+(\d{3}\s+){2}\d{2}\b/',                                 //dd ddd ddd dd
            '/\b\d{2}\s+\(\d{3}\)\s+\d{3}\s+\d{3}\b/',                          //dd (ddd) ddd ddd
            '/\b\d{2}\s+\d{4}\s+\d{3}\s+\d{2}\b/',                              //dd dddd ddd dd
            '/\b\d{2}\s+\(\d{3}\)\s+(\d{2}\s+){2}\d{2}\b/',                     //dd (ddd) dd dd dd
            '/\b(\d{2}\s+)?(\d{3}\s+){2}\d{3}\b/u',                             //dd ddd ddd ddd.  Also matches similar local format.
            '/\b\d{2}\s+(\d{3}\s+){2}\d\s+\d{2}\b/',                            //dd ddd ddd d dd
            '/\b\d{2}\s+\d{3}\s+(\d{2}\s+){2}\d{2}\b/',                         //dd ddd dd dd dd

        //Excluding country code:
            '/(?<!\+)\b\d{3}\s+(\d{2}\s+){2}\d{2}\b/',                          //ddd dd dd dd.  @todo Review this.
            '/\b\d{2}\s+\d{3}\s+\d{2}\s+\d{2}\b/',                              //dd ddd dd dd
            '/\b\d{2}\.\d{3}\.\d{2}\.\d{2}\b/',                                 //dd.ddd.dd.dd
            '/\b(\d{3}\.){2}\d{3}\b/',                                          //ddd.ddd.ddd
            '/\b\d{2}\s+\d{3}(\s+|-)\d{6}\b/',                                  //dd ddd dddddd
        ],
        'gb' => [
        //Including country code:
            //With regional code leading zero:

            '/\b\d{2}\s+\(\d\)\d\s+(\d{2}\s+){3}\d\s+\d{2}\b/',                 //dd (d)d dd dd dd d dd

            '/\b\d{2}\s+\(\d\)\d{3}\s+\d{3}\s+\d{2}\s+\d{2}\b/',                //dd (d)ddd ddd dd dd
            '/\b\d{2}\s+\(\d\)(\d{3}\s+){2}\d\s+\d{3}\b/',                      //dd (d)ddd ddd d ddd
            '/\b\d{2}\s+\(\d\)\s*\d{3}\s+(\d{2}\s+){2}\d{3}\b/',                //dd (d)ddd dd dd ddd
            '/\b\d{2}\s+\(\d\)\d{3}\s+\d{2}\s+\d{3}\s+\d{2}\b/',                //dd (d)ddd dd ddd dd
            '/\b\d{2}\s+\(\d\)\d{1,2}\s+\d{3}\s+\d{2}\s+\d{3}\b/',              //dd (d)d[d] ddd dd ddd

            '/(\b\d{2}\s+)?\(?\b0\)?\s*\d{4}\s+\d{3}\s+\d{3}\b/',               //dd (0)dddd ddd ddd.  Also matches similar local format.
            '/\b\d{2}\s+\(?0\)?\s*\d{3}\s+\d{4}\s+\d{3}\b/',                    //dd (0)ddd dddd ddd

            '/\b\d{2}\s+\(?0\)?\d{4}\s+\d{5,6}\b/',                             //dd (0)dddd ddddd[d]
            '/\b\d{2}\s+\(?0\)?\d{3}\s+\d{7}\b/',                               //dd (0)ddd ddddddd

            //Excluding regional code leading zero:

            '/\b\d{2}\s+\d{3}\s+\d{4}\s+\d{3}\b/',                              //dd ddd dddd ddd
            '/\b\d{2}\s+\d{4}\s+\d{3}\s+\d{3}\b/',                              //dd dddd ddd ddd

            '/\b\d{2}\s+\d{3}\s+\d{7}\b/',                                      //dd ddd ddddddd
            '/\b\d{2}-\d{4}-\d{6}\b/',                                          //dd dddd dddddd

            //Others:
            '/\b\d{3}\s+\d{2}\s+\d{7}\b/',                                      //ddd dd ddddddd.  Ireland.
            '/\b00\d{2}\s+(\d{3}\s+){2}\d{4}\b/',                               //00dd ddd ddd dddd.  @todo Move this up?
            '/\b00\d{6}\s+\d{6}\b/',                                            //00dddddd dddddd

        //Excluding country code:
            '/\(\d{6}\)\s+\d{4,5}\b/',                                          //(dddddd) dddd[d]
            '/\(\d{5}\)\s+\d{5,6}\b/',                                          //(ddddd) ddddd[d]
            '/\(?\d{4}\)?\s+\d{3}\s+\d{4}\b/',                                  //(dddd) ddd dddd
            '/\b\d{3}\s+\d{4}\s+\d{4}\b/',                                      //ddd dddd dddd
            '/\b\d{5}\s+\d{6}\b/',                                              //ddddd dddddd
            '/\b\d{5}\s+\d{3}\s+\d{3}\b/',                                      //ddddd ddd ddd
            '/\b\d{4}\s+(\d{4}|\d{6})\b/',                                      //dddd dddd[dd]
            '/\b\d{4}\s+\d{2}\s+\d{2}\b/',                                      //dddd dd dd
        ],
        'fr' => [
        //Including country code:
            '/\b(00)?\d{2}\s*(\(\d\))?\d{3}(\s+|[\+\-])\d{6}\b/',               //[00]dd [(d)]ddd dddddd
            '/\b\d{2}\s+\(\d\)\d\s+\d{2}\s+\d{6}\b/',                           //dd (d)d dd dddddd

            '/\b\d{2}\s*\(\d\)\d{1,2}(\s+|\/)\d{8}\b/',                         //dd(d)d[d] dddddddd.  "\/" deals with a one-off.
            '/\b\d{2}\s*\(\d{1,2}\)\d{8}\b/',                                   //dd(d[d])dddddddd

            '/\b\d{2,4}\s+\d\s+(\d{2}\s+){3}\d{2}\b/',                          //dd[d[d]] d dd dd dd dd
            '/(\b\d{2}\s*)?\(?\s*\d{1,2}\)?\s*\d{1,2}(\s+|\.)(\d{2}(\s+|\.)){3}\d{2}\b/',   //dd (d)d[d] dd dd dd dd.  Also matches similar local format.
            '/\b\d{2,3}\s*\(\d{1,2}\)\s*(\d{2}\s+){3}\d{2}\b/',                 //dd[d] (d[d]) dd dd dd dd

            '/\b\d{2}\s+\(\d\)\d\s+\d\s+\d{2}\s+\d{2}\s+\d{2}\b/',              //dd (d)d d dd dd dd
            '/\b\d{2}\s+(\(\d\))?\d\s+(\d{2}\s+(\+\s+)?){2}\d{2}\b/',           //dd [(d)]d dd dd dd

            '/\b\d{2}\(\d\)(\d{3}\s+){2}\d{2}\b/',                              //dd(d)ddd ddd dd
            '/\b\d{4}\s+\d\s+(\d{3}\s+){2}\d{2}\b/',                            //dddd d ddd ddd dd

            '/\b\d{3}\s+\d{2}\s+\d{3}\s+\d{3}\b/',                              //ddd dd ddd ddd.  Monaco.
            '/\b\d{4}\s+\d\s+\d{2}\s+\d{3}\s+\d{3}\b/',                         //dddd d dd ddd ddd
            '/\b\d{3}\s+\(\d\)\d{2}\s+\d{3}\s+\d{3}\b/',                        //ddd (d)dd ddd ddd
            '/\b(00)?\d{2}[\s+\.]\(\d\)\s*\d\s+\d{2}[\s+\.]\d{3}[\s+\.]\d{3}\b/',   //[00]dd (d) d dd ddd ddd

            '/\b\d{2}\s+\(\d\)\d\s+(\d{2}\s+){2}\d{4}\b/',                      //dd (d)d dd dd dddd
            '/\b\d{2}\s+\d{3}\s+\d{2}\s+\d{4}\b/',                              //dd ddd dd dddd

            //Others:
            '/\b\d{2}\s+\d{3}\s+\d{5}\b/',                                      //dd ddd ddddd
            '/\b\d{2}\s+\(\d\)\d\s+\d{3}\s+\d{5}\b/',                           //dd (d)d ddd ddddd
            '/(\b\d{2}\s*)?\(\+?\d{1,2}\)?\s*\d{9,10}\b/',                      //dd(dd)ddddddddd[d].  Also matches similar local format.
            '/\b\d{2}\s+\(\d\)\d\s+\d\s+\d{3}\s+\d{2}\s+\d{2}\b/',              //dd (d)d d ddd dd dd
            '/\b\d{2}\s+\(\d\)\d\s+\d{2}\s+\d{4}\s+\d{2}\b/',                   //dd (d)d dd dddd dd
            '/(\b\d{2}\s*)?\(?\b\d\)?\s*\d{3}\s+(\d{2}\s+){2}\d{2}\b/',         //dd d ddd dd dd dd.  Also matches similar local format.
            '/\b\d{4}\s+\d\s+(\d{2}\s+){3}\d\b/',                               //dddd d dd dd dd d
            '/\b\d{2}\s+\(\d\)\d\s+(\d{2}\s+){3}\d\b/',                         //dd (d)d dd dd dd d
            '/\b\d{2}\s+\(\d\)\d\s+\d{2}\s+\d\s+\d{3}\s+\d{2}\b/',              //dd (d)d dd d ddd dd
            '/\b\d{2}\s+\(\d\)\d\s+(\d{2}\s+){2}-?\d\s+\d{2}\b/',               //dd (d)d dd dd d dd
        ],
        'ch' => [
        //Including country code:
            '/(\b(00)?\d{2}\s+)?\(?\b\d\)?\d{1,2}\s+\d{3}(\s+|\.)\d{2}(\s+|\.)\d{2}\b/u',   //[00]dd [(0)]d[d] ddd dd dd.  OFCOM international format.  Also matches similar local format.

            '/\b\d{2}\s+\(\d\)\d{3}\s+\d{6}\b/',                                //dd (d)ddd dddddd
            '/\b\d{2}\s+\(\d\)\s*\d{2}\s+\d{7}\b/',                             //dd (d)dd ddddddd

            '/\b\d{2}\s*\(\s*\d\)\s*\d{2}\s+\d{3}\s+\d{4}\b/',                  //dd (d)dd ddd dddd
            '/\b(00)?(\d{2}\s+){2}\d{3}\s+\d{4}\b/',                            //[00]dd dd ddd dddd

            //Others:
            '/\b\d{2}\s+\(\d\)\d{3}\s+\d{2}\s+\d{4}\b/',                        //dd (d)ddd dd dddd
            '/\b\d{2}\s+\(\d\)\s+\d{2}\s+\d{4}\s+\d{3}\b/',                     //dd (d) dd dddd ddd
            '/\b\d{2}\s+\(\d\)(\d{2}\s+){3}\d{3}\b/',                           //dd (d)dd dd dd ddd
            '/\b\d{2}\s+\(\d\)(\d{2}\s+){2}\d{3}\s+\d{2}\b/',                   //dd (d)dd dd ddd dd
            '/\b\d{2}\s+\(\d\)\d\s+(\d{3}\s+){2}\d{2}\b/',                      //dd (d)d ddd ddd dd
        ],
        'it' => [
        //Including country code:
            '/\b\d{2}\s+\(\d\)\d\s+(\d\s+){2}(\d{2}\s+){2}\d{2}\b/',            //dd (d)d d d dd dd dd

            '/\b\d{2}\s+\d{4}(\s+|[\+\-])\d{5,6}\b/',                           //dd dddd ddddd[d].  Also serves Denmark.
            '/\b\d{2}\s+\(\d\)\d\s+\d{7}\b/',                                   //dd (d)d ddddddd

            '/\b(00)?\d{2}\s+\d{2}\s+\d{5,9}\b/u',                              //00dd dd ddddd[d[d[d[d]]]]

            '/\b00\+?\d{2}\s+\d{3}\s+\d{6,7}\b/',                               //00dd ddd dddddd[d]
            '/\b\d{2}\+\d{2}\s+\d{3}\s+\d{4}\b/',                               //dd+dd ddd dddd

            '/\b\d{4}\s+\d{9,10}\b/',                                           //dddd ddddddddd[d]
            '/\b00\d{3}\s+\d{7}\b/',                                            //00ddd ddddddd

            //Others:
            '/\b\d{2}\.\d{2}\.\d{3}\.\d{5}\b/',                                 //dd dd ddd ddddd
            '/\b\d{2}(\s+|\.)\d{9}\b/',                                         //dd.ddddddddd
        ],
        'de' => [
        //Including country code:
            '/\b\d{2}\s+\d{3}\s+\d{2}\s+\d{3}\b/',                              //dd ddd dd ddd
            '/\b\d{4}\s+\d{3}\s+\d{5}\b/',                                      //dddd ddd ddddd
            '/\b\d{5}\s+\d{3}\s+\d{2}\s+\d{2}\b/',                              //ddddd ddd dd dd
        ],
        'dk' => [
        //Including country code:
            '/\b\d{2}\s+\(\d\)\d\s+\d{3}\s+\d{4}\b/',                           //dd (d)d ddd dddd
            '/\b(00)?\d{2}\s+\d{8}\b/',                                         //[00]dd dddddddd
        ],
        'us' => [
        //Including country code:
            '/\b\d\s+\d{3}\s+\d{7}\b/',                                         //d ddd ddddddd
            '/\(?\b(00)?\d\)?\s+(\d{3}\s+){2}\d{4}\b/',                         //[00]d ddd ddd dddd
        ],
        'nl' => [
        //Including country code:
            '/\b\d{2}\s+\(\d\)\d\s+\d{2}\s+\d{3}\s+\d{3}\b/',                   //dd (d)d dd ddd ddd
            '/\b\d{2}\s+\d\s+\d{3}\s+\d{5}\b/',                                 //dd d ddd ddddd
            '/\b\d{2}\s+\d\s+\d{8}\b/',                                         //dd d dddddddd
        ],
        'ru' => [
        //Including country code:
            '/\b\d(\s+|-)(\d{3}(\s+|-)){2}\d{2}(\s+|-)\d{2}\b/',                //d ddd ddd dd dd
            '/\b\d(\s+|\()\d{3}(\s+|\))\d{6,7}\b/',                             //d ddd dddddd[d]
        ],
        'ae' => [
        //Including country code:
            '/\b00\d{3}\s+\d{2}\s+\d{7}\b/',                                    //00ddd dd ddddddd
        ],
        'th' => [
        //Including country code:
            '/\b\d{2}\s+\d{3}\s+\d{8}\b/',                                      //dd ddd dddddddd
        ],
        'pt' => [
        //Including country code:
            '/\b(\d{3}\s+){2}\d{6}\b/',                                         //ddd ddd dddddd
        ],
        'au' => [
        //Including country code:
            '/\+\d{2}\s+\d\s+(\d{3}\s+){2}\d{2}/',                              //dd d ddd ddd dd.  A special case - note the leading "+" and lack of a trailing "\b".
        ],
    ];

    /**
     * @return array
     */
    private function getRegExps()
    {
        $regExps = [];

        foreach (self::$countryRegExps as $countryRegExps) {
            foreach ($countryRegExps as $countryRegExp) {
                $regExps[] = $countryRegExp;
            }
        }

        return $regExps;
    }

    /**
     * Returns TRUE if there appears to be a telephone number in the string, or FALSE otherwise.
     *
     * @return bool
     */
    public function found()
    {
        /* @var $obfuscatorPlugin Obfuscator */
        $obfuscatorPlugin = $this
            ->getStringspector()
            ->getPlugin('obfuscator')
        ;

        $regExps = $this->getRegExps();

        foreach ($regExps as $regExp) {
            if ($obfuscatorPlugin->matchAll($regExp)) {
                return true;
            }
        }

        return false;
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

        $regExps = $this->getRegExps();

        foreach ($regExps as $regExp) {
            $obfuscatorPlugin->obfuscateAll($regExp, $replacement);
        }
    }
}
