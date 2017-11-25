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
 * A user account on an existing system, such as a private system (LMS or
 * intranet) or a public system (social networking site).
 */
class AgentAccount implements VersionableInterface, ComparableInterface
{
    use ArraySetterTrait, FromJSONTrait, AsVersionTrait, SignatureComparisonTrait;

    /** @var string */
    protected $name;

    /** @var string IRL */
    protected $homePage;

    /**
     * AgentAccount constructor.
     *
     * $arg elements:
     * * var string $homePage IRL
     * * var string $name
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
    public function setName($value) { $this->name = $value; return $this; }

    /**
     * @return string
     */
    public function getName() { return $this->name; }

    /**
     * @param string $value IRL
     * @return $this
     */
    public function setHomePage($value) { $this->homePage = $value; return $this; }

    /**
     * @return string
     */
    public function getHomePage() { return $this->homePage; }
}
