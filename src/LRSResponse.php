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
 * Holds the result of sending a request to a LRS.
 */
class LRSResponse
{
    use ArraySetterTrait;

    /** @var bool */
    public $success;

    /** @var About|Activity|ActivityProfile|AgentProfile|Person|State|Statement|Statement[]|StatementsResult|string[]|string */
    public $content;

    /** @var array */
    public $httpResponse;

    /**
     * LRSResponse constructor.
     *
     * @param bool $success
     * @param mixed $content
     * @param array $httpResponse
     */
    public function __construct($success, $content, $httpResponse) {
        $this->success = (bool) $success;
        $this->content = $content;
        $this->httpResponse = $httpResponse;
    }
}
