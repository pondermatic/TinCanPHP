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
 * A map of the types of learning activity context that this Statement is
 * related to.
 *
 * Many Statements do not just involve one (Object) Activity that is the focus,
 * but relate to other contextually relevant Activities.
 * The "contextActivities" property allow for these related Activities to be
 * represented in a structured manner.
 *
 * Valid context types are: parent, "grouping", "category" and "other".
 */
class ContextActivities implements VersionableInterface, ComparableInterface
{
    use ArraySetterTrait, FromJSONTrait, AsVersionTrait, SignatureComparisonTrait;

    /** @var Activity[] */
    protected $category = array();

    /** @var Activity[] */
    protected $parent = array();

    /** @var Activity[] */
    protected $grouping = array();

    /** @var Activity[] */
    protected $other = array();

    /**
     * ContextActivities constructor.
     *
     * $arg elements;
     * * var Activity|array $category
     * * var Activity|array $grouping
     * * var Activity|array $other
     * * var Activity|array $parent
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_fromArray($arg);
        }
    }

    /**
     * @param string $prop
     * @param Activity|array $value
     * @throws \InvalidArgumentException if $value is not an Activity, an array of Activity properties,
     * or an array of Activities, or an array of an array of Activity properties
     * @return $this
     */
    private function _listSetter($prop, $value) {
        if (is_array($value)) {
            if (isset($value['id'])) {
                array_push($this->$prop, new Activity($value));
            }
            else {
                foreach ($value as $k => $v) {
                    if (! $value[$k] instanceof Activity) {
                        $value[$k] = new Activity($value[$k]);
                    }
                }
                $this->$prop = $value;
            }
        }
        elseif ($value instanceof Activity) {
            array_push($this->$prop, $value);
        }
        else {
            throw new \InvalidArgumentException('type of arg1 must be Activity, array of Activity properties, or array of Activity/array of Activity properties');
        }
        return $this;
    }

    /**
     * @param Activity|array $value
     * @return ContextActivities
     */
    public function setCategory($value) { return $this->_listSetter('category', $value); }

    /**
     * @return Activity[]
     */
    public function getCategory() { return $this->category; }

    /**
     * @param Activity|array $value
     * @return ContextActivities
     */
    public function setParent($value) { return $this->_listSetter('parent', $value); }

    /**
     * @return Activity[]
     */
    public function getParent() { return $this->parent; }

    /**
     * @param Activity|array $value
     * @return ContextActivities
     */
    public function setGrouping($value) { return $this->_listSetter('grouping', $value); }

    /**
     * @return Activity[]
     */
    public function getGrouping() { return $this->grouping; }

    /**
     * @param Activity|array $value
     * @return ContextActivities
     */
    public function setOther($value) { return $this->_listSetter('other', $value); }

    /**
     * @return Activity[]
     */
    public function getOther() { return $this->other; }
}
