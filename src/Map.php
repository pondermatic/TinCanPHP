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
 * Class Map
 *
 * @method void unset(string|int $code)
 */
abstract class Map implements VersionableInterface
{
    use FromJSONTrait;

    /** @var array */
    protected $_map;

    /**
     * Map constructor.
     *
     * $arg elements:
     * * var array $map
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_map = $arg;
        }
        else {
            $this->_map = array();
        }
    }

    /**
     * Collects defined object properties for a given version into an array.
     *
     * @param null $version
     * @return array|null
     */
    public function asVersion($version = null) {
        return $this->isEmpty() ? null : $this->_map;
    }

    /**
     * @param string|int $code
     * @param mixed $value
     */
    public function set($code, $value) {
        $this->_map[$code] = $value;
    }

    /**
     * @param string|int $code
     */
    private function _unset($code) {
        unset($this->_map[$code]);
    }

    /**
     * @return bool
     */
    public function isEmpty() {
        return count($this->_map) === 0;
    }

    /**
     * @param string $func
     * @param array $args
     * @throws \BadMethodCallException if method does not exist
     */
    public function __call($func, $args) {
        switch ($func) {
            case 'unset':
                return $this->_unset($args[0]);
            break;
            default:
                throw new \BadMethodCallException(get_class($this) . "::$func() does not exist");
        }
    }
}
