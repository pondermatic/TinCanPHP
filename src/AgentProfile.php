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
 * The Agent Profile Resource is much like the State Resource, allowing for
 * arbitrary key / document pairs to be saved which are related to an Agent.
 */
class AgentProfile extends Document
{
    /** @var Agent|Group */
    protected $agent;

    /**
     * AgentProfile constructor.
     *
     * $arg elements:
     * * var Agent|array $agent
     * * var string $content
     * * var string $contentType
     * * var string $etag HTTP 1.1 entity tag
     * * var string $id
     * * var \DateTime|string $timestamp ISO 8601
     *
     * @param array $arg
     */
    public function __construct() {
        parent::__construct(func_get_args());
    }

    /**
     * @param Agent|array $value
     * @return $this
     */
    public function setAgent($value) {
        if ((! $value instanceof Agent && ! $value instanceof Group) && is_array($value)) {
            if (isset($value['objectType']) && $value['objectType'] === 'Group') {
                $value = new Group($value);
            }
            else {
                $value = new Agent($value);
            }
        }

        $this->agent = $value;

        return $this;
    }

    /**
     * @return Agent|Group
     */
    public function getAgent() { return $this->agent; }
}
