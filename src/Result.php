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
 * Represents a measured outcome related to the Statement in which it is included.
 */
class Result implements VersionableInterface, ComparableInterface
{
    use ArraySetterTrait, FromJSONTrait, AsVersionTrait, SignatureComparisonTrait;

    /** @var Score */
    protected $score;

    /** @var bool */
    protected $success;

    /** @var bool */
    protected $completion;

    /** @var string ISO 8601 format */
    protected $duration;

    /** @var string */
    protected $response;

    /** @var Extensions */
    protected $extensions;

    /**
     * Result constructor.
     *
     * $arg elements:
     * * var bool $completion
     * * var string $duration ISO 8601 format
     * * var Extensions|array $extensions
     * * var Score|array $score
     * * var bool $success
     * * var string $response
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_fromArray($arg);
        }

        if (! isset($this->extensions)) {
            $this->setExtensions(array());
        }
    }

    /**
     * @param array $result
     * @param Version|string $version
     */
    private function _asVersion(&$result, $version) {
        //
        // empty string is an invalid duration
        //
        if (isset($result['duration']) && $result['duration'] == '') {
            unset($result['duration']);
        }
    }

    /**
     * @param Score|array $value
     * @return $this
     */
    public function setScore($value) {
        if (! $value instanceof Score && is_array($value)) {
            $value = new Score($value);
        }

        $this->score = $value;

        return $this;
    }

    /**
     * @return Score
     */
    public function getScore() { return $this->score; }

    /**
     * @param bool $value
     * @return $this
     */
    public function setSuccess($value) { $this->success = (bool) $value; return $this; }

    /**
     * @return bool
     */
    public function getSuccess() { return $this->success; }

    /**
     * @param bool $value
     * @return $this
     */
    public function setCompletion($value) { $this->completion = (bool) $value; return $this; }

    /**
     * @return bool
     */
    public function getCompletion() { return $this->completion; }

    /**
     * @param string $value ISO 8601 format
     * @return $this
     */
    public function setDuration($value) { $this->duration = $value; return $this; }

    /**
     * @return string ISO 8601 format
     */
    public function getDuration() { return $this->duration; }

    /**
     * @param string $value
     * @return $this
     */
    public function setResponse($value) { $this->response = $value; return $this; }

    /**
     * @return string
     */
    public function getResponse() { return $this->response; }

    /**
     * @param Extensions|array $value
     * @return $this
     */
    public function setExtensions($value) {
        if (! $value instanceof Extensions) {
            $value = new Extensions($value);
        }

        $this->extensions = $value;

        return $this;
    }

    /**
     * @return Extensions
     */
    public function getExtensions() { return $this->extensions; }
}
