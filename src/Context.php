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

use InvalidArgumentException;

/**
 * Context that gives the Statement more meaning.
 *
 * It can store information such as the instructor for an experience, if this
 * experience happened as part of a team-based Activity, or how an experience
 * fits into some broader activity.
 *
 * Examples: a team the Actor is working with, altitude at which a scenario was
 * attempted in a flight simulator.
 */
class Context implements VersionableInterface, ComparableInterface
{
    use ArraySetterTrait, FromJSONTrait, AsVersionTrait, SignatureComparisonTrait;

    /** @var string UUID */
    protected $registration;

    /** @var Agent|Group */
    protected $instructor;

    /** @var Group */
    protected $team;

    /** @var ContextActivities */
    protected $contextActivities;

    /** @var string */
    protected $revision;

    /** @var string */
    protected $platform;

    /** @var string as defined in RFC 5646 */
    protected $language;

    /** @var StatementRef */
    protected $statement;

    /** @var Extensions */
    protected $extensions;

    /**
     * Context constructor.
     *
     * $arg elements:
     * * var ContextActivities|array $contextActivities
     * * var Extensions|array $extensions
     * * var Agent|Group|array $instructor
     * * var string $language as defined in RFC 5646
     * * var string $platform
     * * var string $registration UUID
     * * var string $revision
     * * var StatementRef|array $statement
     * * var Group|array $team
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_fromArray($arg);
        }

        foreach (
            [
                'contextActivities',
                'extensions',
            ] as $k
        ) {
            $method = 'set' . ucfirst($k);

            if (! isset($this->$k)) {
                $this->$method(array());
            }
        }
    }

    /**
     * @param string $value UUID
     * @throws InvalidArgumentException if $value does not match a UUID pattern
     * @return $this
     */
    public function setRegistration($value) {
        if (isset($value) && ! preg_match(Util::UUID_REGEX, $value)) {
            throw new InvalidArgumentException('arg1 must be a UUID');
        }
        $this->registration = $value;
        return $this;
    }

    /**
     * @return string UUID
     */
    public function getRegistration() { return $this->registration; }

    /**
     * @param Agent|Group|array $value
     * @return $this
     */
    public function setInstructor($value) {
        if (! ($value instanceof Agent || $value instanceof Group) && is_array($value)) {
            if (isset($value['objectType']) && $value['objectType'] === "Group") {
                $value = new Group($value);
            }
            else {
                $value = new Agent($value);
            }
        }

        $this->instructor = $value;

        return $this;
    }

    /**
     * @return Agent|Group
     */
    public function getInstructor() { return $this->instructor; }

    /**
     * @param Group|array $value
     * @return $this
     */
    public function setTeam($value) {
        if (! $value instanceof Group && is_array($value)) {
            $value = new Group($value);
        }

        $this->team = $value;

        return $this;
    }

    /**
     * @return Group
     */
    public function getTeam() { return $this->team; }

    /**
     * @param ContextActivities|array $value
     * @return $this
     */
    public function setContextActivities($value) {
        if (! $value instanceof ContextActivities) {
            $value = new ContextActivities($value);
        }

        $this->contextActivities = $value;

        return $this;
    }

    /**
     * @return ContextActivities
     */
    public function getContextActivities() { return $this->contextActivities; }

    /**
     * @param string $value
     * @return $this
     */
    public function setRevision($value) { $this->revision = $value; return $this; }

    /**
     * @return string
     */
    public function getRevision() { return $this->revision; }

    /**
     * @param string $value
     * @return $this
     */
    public function setPlatform($value) { $this->platform = $value; return $this; }

    /**
     * @return string
     */
    public function getPlatform() { return $this->platform; }

    /**
     * @param string $value as defined in RFC 5646
     * @return $this
     */
    public function setLanguage($value) { $this->language = $value; return $this; }

    /**
     * @return string as defined in RFC 5646
     */
    public function getLanguage() { return $this->language; }

    /**
     * @param StatementRef|array $value
     * @return $this
     */
    public function setStatement($value) {
        if (! $value instanceof StatementRef && is_array($value)) {
            $value = new StatementRef($value);
        }

        $this->statement = $value;

        return $this;
    }

    /**
     * @return StatementRef
     */
    public function getStatement() { return $this->statement; }

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
