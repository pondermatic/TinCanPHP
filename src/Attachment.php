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

class Attachment implements VersionableInterface, ComparableInterface
{
    use ArraySetterTrait, FromJSONTrait, AsVersionTrait, SignatureComparisonTrait;

    /** @var array */
    static private $signatureSkipProperties = array('display', 'description');

    /** @var string IRI */
    protected $usageType;

    /** @var LanguageMap */
    protected $display;

    /** @var LanguageMap */
    protected $description;

    /** @var string Internet Media Type */
    protected $contentType;

    /** @var int */
    protected $length;

    /** @var string */
    protected $sha2;

    /** @var string IRL */
    protected $fileUrl;

    /** @var string */
    protected $_content;

    /**
     * Attachment constructor.
     *
     * $arg elements:
     * * var string $content
     * * var string $contentType Internet Media Type
     * * var LanguageMap|array $description
     * * var LanguageMap|array $display
     * * var string $fileUrl IRL
     * * var int $length
     * * var string $sha2
     * * var string $usageType IRI
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_fromArray($arg);

            if (isset($arg['content'])) {
                $this->setContent($arg['content']);
            }
        }

        foreach (
            [
                'display',
                'description',
            ] as $k
        ) {
            $method = 'set' . ucfirst($k);

            if (! isset($this->$k)) {
                $this->$method(array());
            }
        }
    }

    /**
     * @param string $value IRI
     * @return $this
     */
    public function setUsageType($value) { $this->usageType = $value; return $this; }

    /**
     * @return string IRI
     */
    public function getUsageType() { return $this->usageType; }

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
     * @param LanguageMap|array $value
     * @return $this
     */
    public function setDescription($value) {
        if (! $value instanceof LanguageMap) {
            $value = new LanguageMap($value);
        }

        $this->description = $value;

        return $this;
    }

    /**
     * @return LanguageMap
     */
    public function getDescription() { return $this->description; }

    /**
     * @param string $value Internet Media Type
     * @return $this
     */
    public function setContentType($value) { $this->contentType = $value; return $this; }

    /**
     * @return string Internet Media Type
     */
    public function getContentType() { return $this->contentType; }

    /**
     * @param int $value
     * @return $this
     */
    public function setLength($value) { $this->length = $value; return $this; }

    /**
     * @return int
     */
    public function getLength() { return $this->length; }

    /**
     * @param string $value
     * @return $this
     */
    public function setSha2($value) { $this->sha2 = $value; return $this; }

    /**
     * @return string
     */
    public function getSha2() { return $this->sha2; }

    /**
     * @param string $value IRL
     * @return $this
     */
    public function setFileUrl($value) { $this->fileUrl = $value; return $this; }

    /**
     * @return string IRL
     */
    public function getFileUrl() { return $this->fileUrl; }

    /**
     * @param string $value
     * @return $this
     */
    public function setContent($value) {
        $this->_content = $value;
        $this->setLength(strlen($value));
        $this->setSha2(hash("sha256", $value));
        return $this;
    }

    /**
     * @return string
     */
    public function getContent() { return $this->_content; }

    /**
     * @return bool
     */
    public function hasContent() { return isset($this->_content); }
}
