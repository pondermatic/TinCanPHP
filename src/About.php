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
 * Contains information about this LRS, including the xAPI version supported.
 *
 * Primarily this resource exists to allow Clients that support multiple xAPI
 * versions to decide which version to use when communicating with the LRS.
 * Extensions are included to allow other uses to emerge.
 */
class About implements VersionableInterface
{
    use ArraySetterTrait, FromJSONTrait, AsVersionTrait;

    /** @var Version[]|string[] */
    protected $version;

    /** @var Extensions */
    protected $extensions;

    /**
     * About constructor.
     *
     * $arg elements:
     * * var Extensions|array $extensions
     * * var Version[]|string[] $version
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_fromArray($arg);
        }

        if (! isset($this->version)) {
            $this->setVersion(array());
        }
        if (! isset($this->extensions)) {
            $this->setExtensions(array());
        }
    }

    /**
     * @param string[] $value
     * @return $this
     */
    public function setVersion($value) { $this->version = $value; return $this; }

    /**
     * @return string[]
     */
    public function getVersion() { return $this->version; }

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
