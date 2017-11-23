<?php
/*
    Copyright 2014 Rustici Software

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
*/

namespace TinCan;

/**
 * A language map is a dictionary where the key is a RFC 5646 Language Tag, and the
 * value is a string in the language specified in the tag. This map SHOULD be populated as fully as possible based on the
 * knowledge of the string in question in different languages.
 *
 * See [RFC 5646 Language Tag](http://tools.ietf.org/html/rfc5646).
 *
 * The shortest valid language code for each language string is generally preferred. The
 * [ISO 639 language code](https://www.loc.gov/standards/iso639-2/php/code_list.php) plus an
 * [ISO 3166-1 country code](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) allows for the designation of
 * basic languages (e.g., `es` for Spanish) and regions (e.g.,
 * `es-MX`, the dialect of Spanish spoken in Mexico). If only the ISO 639 language code is known for certain,
 * do not guess at the possible ISO 3166-1 country code. For example, if
 * only the primary language is known (e.g., English) then use the top level
 * language tag `en`, rather than `en-US`. If the specific regional variation is known, then use the full language code.
 *
 * __Note:__ For Chinese languages, the significant linguistic diversity represented by `zh` means that the ISO 639 language
 * code is generally insufficient.
 *
 * The content of strings within a language map is plain text. It is expected that any formatting code
 * such as HTML tags or markdown will not be rendered, but will be displayed as code when this string is
 * displayed to an end user. An important exception to this is if language map Object is used in an extension and
 * the owner of that extension IRI explicitly states that a particular form of code will be rendered.
 */
class LanguageMap extends Map
{
    /**
     * Determines the best language tag to use.
     *
     * @param string|null $acceptLanguage defaults to $_SERVER['HTTP_ACCEPT_LANGUAGE'],
     * {@see https://tools.ietf.org/html/rfc7231#section-5.3.5 RFC 7231 section 5.3.5}
     * @return string language tag {@see https://tools.ietf.org/html/rfc5646 RFC 5646}
     */
    public function getNegotiatedLanguageString ($acceptLanguage = null) {
        $negotiator = new \Negotiation\LanguageNegotiator();
        if ($acceptLanguage === null) {
            //
            // include the q=0 on * because of an issue in the library not picking up
            // the earlier configured language correctly, see
            // https://github.com/willdurand/Negotiation/issues/83
            //
            $acceptLanguage = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE']. ', *;q=0' : '*';
        }
        $availableLanguages = array_keys($this->_map);
        /** @var \Negotiation\AcceptLanguage $preferredLanguage */
        $preferredLanguage = $negotiator->getBest($acceptLanguage, $availableLanguages);

        $key = $availableLanguages[0];
        if (isset($preferredLanguage)) {
            $key = $preferredLanguage->getValue();
        }
        elseif (isset($this->_map['und'])) {
            $key = 'und';
        }

        return $this->_map[$key];
    }
}
