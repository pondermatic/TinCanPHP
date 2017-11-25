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
 * The Activity Profile Resource is much like the State Resource, allowing for
 * arbitrary key / document pairs to be saved which are related to an Activity.
 */
class ActivityProfile extends Document
{
    /** @var Activity */
    protected $activity;

    /**
     * ActivityProfile constructor.
     *
     * $arg elements:
     * * var Activity|array $activity
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
}
