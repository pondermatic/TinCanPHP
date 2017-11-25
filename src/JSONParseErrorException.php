<?php
/*
    Copyright 2016 Rustici Software

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

class JSONParseErrorException extends \Exception
{
    /** @var string */
    private static $format = 'Invalid JSON "%s": %s (%d)';

    /** @var string */
    private $malformedValue;

    /** @var int one of the JSON_ERROR_X constants returned by json_last_error() */
    private $jsonErrorNumber;

    /** @var string */
    private $jsonErrorMessage;

    /**
     * JSONParseErrorException constructor.
     *
     * @param string $malformedValue
     * @param int $jsonErrorNumber one of the JSON_ERROR_X constants returned by json_last_error()
     * @param string $jsonErrorMessage
     * @param \Exception|null $previous
     */
    public function __construct($malformedValue, $jsonErrorNumber, $jsonErrorMessage, \Exception $previous = null) {
        $this->malformedValue   = $malformedValue;
        $this->jsonErrorNumber  = (int) $jsonErrorNumber;
        $this->jsonErrorMessage = $jsonErrorMessage;

        $message = sprintf(self::$format, $malformedValue, $jsonErrorMessage, $jsonErrorNumber);

        parent::__construct($message, $jsonErrorNumber, $previous);
    }

    /**
     * @return string
     */
    public function malformedValue() {
        return $this->malformedValue;
    }

    /**
     * @return int one of the JSON_ERROR_X constants returned by json_last_error()
     */
    public function jsonErrorNumber() {
        return $this->jsonErrorNumber;
    }

    /**
     * @return string
     */
    public function jsonErrorMessage() {
        return $this->jsonErrorMessage;
    }
}
