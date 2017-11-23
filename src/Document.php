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

// TODO: should this be an implementation of an interface?
abstract class Document
{
    use ArraySetterTrait;

    /** @var string */
    protected $id;

    /** @var string */
    protected $contentType;

    /** @var string */
    protected $content;

    /** @var string HTTP 1.1 entity tag */
    protected $etag;

    /** @var string ISO 8601 */
    protected $timestamp;

    /**
     * Document constructor.
     *
     * $arg elements:
     * * var string $content
     * * var string $contentType
     * * var string $etag HTTP 1.1 entity tag
     * * var string $id
     * * var \DateTime|string $timestamp ISO 8601
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_fromArray($arg);
        }
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setId($value) { $this->id = $value; return $this; }

    /**
     * @return string
     */
    public function getId() { return $this->id; }

    /**
     * @param string $value
     * @return $this
     */
    public function setContentType($value) { $this->contentType = $value; return $this; }

    /**
     * @return string
     */
    public function getContentType() { return $this->contentType; }

    /**
     * @param string $value
     * @return $this
     */
    public function setContent($value) { $this->content = $value; return $this; }

    /**
     * @return string
     */
    public function getContent() { return $this->content; }

    /**
     * @param string $value HTTP 1.1 entity tag
     * @return $this
     */
    public function setEtag($value) { $this->etag = $value; return $this; }

    /**
     * @return string HTTP 1.1 entity tag
     */
    public function getEtag() { return $this->etag; }

    /**
     * @param \DateTime|string $value ISO 8601
     * @throws \InvalidArgumentException if $value is not a DateTime object or a string
     * @return $this
     */
    public function setTimestamp($value) {
        if (isset($value)) {
            if ($value instanceof \DateTime) {
                // Use format('c') instead of format(\DateTime::ISO8601) due to bug
                // in format(\DateTime::ISO8601) that generates an invalid timestamp.
                $value = $value->format('c');
            }
            elseif (is_string($value)) {
                $value = $value;
            }
            else {
                throw new \InvalidArgumentException('type of arg1 must be string or DateTime');
            }
        }

        $this->timestamp = $value;

        return $this;
    }

    /**
     * @return \DateTime|string
     */
    public function getTimestamp() { return $this->timestamp; }
}
