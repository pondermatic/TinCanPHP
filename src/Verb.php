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
 * Is the action being done by the Actor within the Activity within a Statement.
 * A Verb represents the "did" in "I did this".
 */
class Verb implements VersionableInterface, ComparableInterface
{
    use ArraySetterTrait, FromJSONTrait, AsVersionTrait, SignatureComparisonTrait;

    /** @var array */
    static private $signatureSkipProperties = array('display');

    /** @var string IRI */
    protected $id;

    /** @var LanguageMap */
    protected $display;

    /**
     * Verb constructor.
     *
     * $arg elements:
     * * var LanguageMap|array $display
     * * var string $id IRI
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_fromArray($arg);
        }

        if (! isset($this->display)) {
            $this->setDisplay(array());
        }
    }

    /**
     * @todo FEATURE: check IRI?
     * @param string $value IRI
     * @return $this
     */
    public function setId($value) { $this->id = $value; return $this; }

    /**
     * @return string IRI
     */
    public function getId() { return $this->id; }

    /**
     * @param LanguageMap|array $value
     * @return $this
     */
    public function setDisplay($value) {
        if (! $value instanceof LanguageMap) {
            $value = new LanguageMap($value);
        }

        $this->display = $value;

        return $this;
    }

    /**
     * @return LanguageMap
     */
    public function getDisplay() { return $this->display; }

    /**
     * @return Verb
     */
    static public function Voided() {
        return new self(
            [
                'id' => 'http://adlnet.gov/expapi/verbs/voided',
                'display' => [
                    'en-US' => 'voided'
                ]
            ]
        );
    }
}
