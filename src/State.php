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
 * Generally, this is a scratch area for Learning Record Providers that do not
 * have their own internal storage, or need to persist state across devices.
 */
class State extends Document
{
    /** @var Activity */
    protected $activity;

    /** @var Agent */
    protected $agent;

    /** @var string UUID */
    protected $registration;

    /**
     * State constructor.
     *
     * $arg elements:
     * * var Activity|array $activity
     * * var Agent|array $agent
     * * var string $content
     * * var string $contentType
     * * var string $etag HTTP 1.1 entity tag
     * * var string $id
     * * var string $registration UUID
     * * var \DateTime|string $timestamp ISO 8601
     *
     * @param array $arg
     */
    public function __construct() {
        parent::__construct(func_get_args());
    }

    /**
     * @param Activity|array $value
     * @return $this
     */
    public function setActivity($value) {
        if (! $value instanceof Activity && is_array($value)) {
            $value = new Activity($value);
        }

        $this->activity = $value;

        return $this;
    }

    /**
     * @return Activity
     */
    public function getActivity() { return $this->activity; }

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
     * @return Agent
     */
    public function getAgent() { return $this->agent; }

    /**
     * @param string $value UUID
     * @throws \InvalidArgumentException if $value does not match a UUID pattern
     * @return $this
     */
    public function setRegistration($value) {
        if (isset($value) && ! preg_match(Util::UUID_REGEX, $value)) {
            throw new \InvalidArgumentException('arg1 must be a UUID');
        }

        $this->registration = $value;

        return $this;
    }

    /**
     * @return string UUID
     */
    public function getRegistration() { return $this->registration; }
}
