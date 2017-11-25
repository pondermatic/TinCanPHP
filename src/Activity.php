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
 * A type of Object making up the "this" in "I did this"; it is something with
 * which an Actor interacted.
 *
 * It can be a unit of instruction, experience, or performance that is to be
 * tracked in meaningful combination with a Verb. Interpretation of Activity is
 * broad, meaning that Activities can even be tangible objects such as a chair
 * (real or virtual). In the Statement "Anna tried a cake recipe", the recipe
 * constitutes the Activity in terms of the xAPI Statement. Other examples of
 * Activities include a book, an e-learning course, a hike, or a meeting.
 */
class Activity implements VersionableInterface, StatementTargetInterface, ComparableInterface
{
    use ArraySetterTrait, FromJSONTrait, AsVersionTrait, SignatureComparisonTrait;

    /** @var array */
    static private $signatureSkipProperties = array('definition');

    /** @inheritdoc */
    private $objectType = 'Activity';

    /** @var string IRI */
    protected $id;

    /** @var ActivityDefinition */
    protected $definition;

    /**
     * Activity constructor.
     *
     * $arg elements:
     * * var ActivityDefinition|array $definition
     * * var string $id IRI
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_fromArray($arg);
        }
    }

    /**
     * @inheritdoc
     */
    public function getObjectType() { return $this->objectType; }

    /**
     * @todo FEATURE: check IRI?
     * @param string $value IRI
     * @return $this
     */
    public function setId($value) { $this->id = $value; return $this; }

    /**
     * @return string IRI
     */
    public function getId() { return $this->id; }

    /**
     * @param ActivityDefinition|array $value
     * @return $this
     */
    public function setDefinition($value) {
        if (! $value instanceof ActivityDefinition && is_array($value)) {
            $value = new ActivityDefinition($value);
        }

        $this->definition = $value;

        return $this;
    }

    /**
     * @return ActivityDefinition
     */
    public function getDefinition() { return $this->definition; }
}
