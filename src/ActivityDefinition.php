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
 * Activity metadata.
 */
class ActivityDefinition implements VersionableInterface
{
    use ArraySetterTrait, FromJSONTrait, AsVersionTrait;

    /** @var string IRI */
    protected $type;

    /** @var LanguageMap */
    protected $name;

    /** @var LanguageMap */
    protected $description;

    /** @var string IRL */
    protected $moreInfo;

    /** @var Extensions */
    protected $extensions;

    /** @var string */
    protected $interactionType;

    /** @var string[] */
    protected $correctResponsesPattern;

    /** @var array */
    protected $choices;

    /** @var array */
    protected $scale;

    /** @var array */
    protected $source;

    /** @var array */
    protected $target;

    /** @var array */
    protected $steps;

    /**
     * ActivityDefinition constructor.
     *
     * $arg elements:
     * * var array $choices
     * * var string[] $correctResponsesPattern
     * * var LanguageMap|array $description
     * * var Extensions|array $extensions
     * * var string $interactionType
     * * var string $moreInfo IRL
     * * var LanguageMap|array $name
     * * var array $scale
     * * var array $source
     * * var array $steps
     * * var array $target
     * * var string $type IRI
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_fromArray($arg);
        }

        foreach (
            [
                'name',
                'description',
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
     * @todo FEATURE: check URI?
     * @param string $value IRI
     * @return $this
     */
    public function setType($value) { $this->type = $value; return $this; }

    /**
     * @return string IRI
     */
    public function getType() { return $this->type; }

    /**
     * @param LanguageMap|array $value
     * @return $this
     */
    public function setName($value) {
        if (! $value instanceof LanguageMap) {
            $value = new LanguageMap($value);
        }

        $this->name = $value;

        return $this;
    }

    /**
     * @return LanguageMap
     */
    public function getName() { return $this->name; }

    /**
     * @param LanguageMap|array $value
     * @return $this
     */
    public function setDescription($value) {
        if (! $value instanceof LanguageMap) {
            $value = new LanguageMap($value);
        }

        $this->description = $value;

        return $this;
    }

    /**
     * @return LanguageMap
     */
    public function getDescription() { return $this->description; }

    /**
     * @param string $value IRL
     * @return $this
     */
    public function setMoreInfo($value) { $this->moreInfo = $value; return $this; }

    /**
     * @return string IRL
     */
    public function getMoreInfo() { return $this->moreInfo; }

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

    /**
     * @param string $value
     * @return $this
     */
    public function setInteractionType($value) { $this->interactionType = $value; return $this; }

    /**
     * @return string
     */
    public function getInteractionType() { return $this->interactionType; }

    /**
     * @param string[] $value
     * @return $this
     */
    public function setCorrectResponsesPattern($value) { $this->correctResponsesPattern = $value; return $this; }

    /**
     * @return string[]
     */
    public function getCorrectResponsesPattern() { return $this->correctResponsesPattern; }

    /**
     * @todo make an array of InteractionComponent
     * @param array $value
     * @return $this
     */
    public function setChoices($value) { $this->choices = $value; return $this; }

    /**
     * @return array
     */
    public function getChoices() { return $this->choices; }

    /**
     * @todo make an array of InteractionComponent
     * @param array $value
     * @return $this
     */
    public function setScale($value) { $this->scale = $value; return $this; }

    /**
     * @return array
     */
    public function getScale() { return $this->scale; }

    /**
     * @todo make an array of InteractionComponent
     * @param array $value
     * @return $this
     */
    public function setSource($value) { $this->source = $value; return $this; }

    /**
     * @return array
     */
    public function getSource() { return $this->source; }

    /**
     * @todo make an array of InteractionComponent
     * @param array $value
     * @return $this
     */
    public function setTarget($value) { $this->target = $value; return $this; }

    /**
     * @return array
     */
    public function getTarget() { return $this->target; }

    /**
     * @todo make an array of InteractionComponent
     * @param array $value
     * @return $this
     */
    public function setSteps($value) { $this->steps = $value; return $this; }

    /**
     * @return array
     */
    public function getSteps() { return $this->steps; }
}
