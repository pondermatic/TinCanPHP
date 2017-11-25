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
 * Basic implementation of a Statement.
 */
abstract class StatementBase implements VersionableInterface, ComparableInterface
{
    use ArraySetterTrait, FromJSONTrait, AsVersionTrait, SignatureComparisonTrait;

    /** @var Agent|Group */
    protected $actor;

    /** @var Verb */
    protected $verb;

    /** @var Activity|Agent|Group|StatementRef|SubStatement */
    protected $target;

    /** @var Result */
    protected $result;

    /** @var Context */
    protected $context;

    /**
     * timestamp *must* store a string because DateTime doesn't
     * support sub-second precision, the setter will take a DateTime and convert
     * it to the proper ISO8601 representation, but if a user needs sub-second
     * precision as afforded by the spec they will have to create their own,
     * they can see TinCan\Util::getTimestamp for an example of how to do so
     *
     * based on the signature comparison tests it seems that DateTime can store
     * subsecond precisions, but just not output them as part of ISO handling?
     * it might be possible to switch to a DateTime and just do manual formatting
     * still with the subsecond value (need to research it fully)
     * @var string
     */
    protected $timestamp;

    /**
     * StatementBase constructor.
     *
     * $arg elements:
     * * var Agent|Group|array $actor
     * * var Context|array $context
     * * var Activity|Agent|Group|StatementRef|SubStatement|array $object
     * * var Result|array $result
     * * var Activity|Agent|Group|StatementRef|SubStatement|array $target
     * * var \DateTime|string $timestamp ISO 8601 timestamp
     * * var Verb|array $verb
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_fromArray($arg);

            //
            // 'object' isn't in the list of properties so ._fromArray doesn't
            // pick it up correctly, but 'target' and 'object' shouldn't be in
            // the args at the same time, so handle 'object' here
            //
            if (isset($arg['object'])) {
                $this->setObject($arg['object']);
            }
        }
    }

    /**
     * @param array $result
     * @param Version|string $version
     */
    private function _asVersion(&$result, $version) {
        if (isset($result['target'])) {
            $result['object'] = $result['target'];
            unset($result['target']);
        }
    }

    /**
     * Compares the instance with a provided instance for determining
     * whether an object received in a signature is a meaningful match.
     *
     * @param StatementBase $fromSig
     * @return array ['success' => bool, 'reason' => string]
     */
    public function compareWithSignature($fromSig) {
        foreach (array('actor', 'verb', 'target', 'context', 'result') as $property) {
            if (! isset($this->$property) && ! isset($fromSig->$property)) {
                continue;
            }
            if (isset($this->$property) && ! isset($fromSig->$property)) {
                return array('success' => false, 'reason' => "Comparison of $property failed: value not in signature");
            }
            if (isset($fromSig->$property) && ! isset($this->$property)) {
                return array('success' => false, 'reason' => "Comparison of $property failed: value not in this");
            }

            $result = $this->$property->compareWithSignature($fromSig->$property);
            if (! $result['success']) {
                return array('success' => false, 'reason' => "Comparison of $property failed: " . $result['reason']);
            }
        }

        if (isset($this->timestamp) || isset($fromSig->timestamp)) {
            if (isset($this->timestamp) && ! isset($fromSig->timestamp)) {
                return array('success' => false, 'reason' => 'Comparison of timestamp failed: value not in signature');
            }
            if (isset($fromSig->timestamp) && ! isset($this->timestamp)) {
                return array('success' => false, 'reason' => 'Comparison of timestamp failed: value not in this');
            }

            $a = new \DateTime ($this->timestamp);
            $b = new \DateTime ($fromSig->timestamp);

            if ($a != $b) {
                return array('success' => false, 'reason' => 'Comparison of timestamp failed: value is not the same');
            }

            //
            // DateTime's diff doesn't take into account subsecond precision
            // even though it can store it, so manually check that
            //
            if ($a->format('u') !== $b->format('u')) {
                return array('success' => false, 'reason' => 'Comparison of timestamp failed: value is not the same');
            }
        }

        return array('success' => true, 'reason' => null);
    }

    /**
     * @param Agent|Group|array $value
     * @return $this
     */
    public function setActor($value) {
        if ((! $value instanceof Agent && ! $value instanceof Group) && is_array($value)) {
            if (isset($value['objectType']) && $value['objectType'] === 'Group') {
                $value = new Group($value);
            }
            else {
                $value = new Agent($value);
            }
        }

        $this->actor = $value;

        return $this;
    }

    /**
     * @return Agent|Group
     */
    public function getActor() { return $this->actor; }

    /**
     * @param Verb|array $value
     * @return $this
     */
    public function setVerb($value) {
        if (! $value instanceof Verb) {
            $value = new Verb($value);
        }

        $this->verb = $value;

        return $this;
    }

    /**
     * @return Verb
     */
    public function getVerb() { return $this->verb; }

    /**
     * @param Activity|Agent|Group|StatementRef|SubStatement|array $value
     * @throws \InvalidArgumentException if $value['objectType'] is not in
     * ['Activity', 'Agent', 'Group', 'StatementRef', 'SubStatement']
     * @return $this
     */
    public function setTarget($value) {
        if (! $value instanceof StatementTargetInterface && is_array($value)) {
            if (isset($value['objectType'])) {
                if ($value['objectType'] === 'Activity') {
                    $value = new Activity($value);
                }
                elseif ($value['objectType'] === 'Agent') {
                    $value = new Agent($value);
                }
                elseif ($value['objectType'] === 'Group') {
                    $value = new Group($value);
                }
                elseif ($value['objectType'] === 'StatementRef') {
                    $value = new StatementRef($value);
                }
                elseif ($value['objectType'] === 'SubStatement') {
                    $value = new SubStatement($value);
                }
                else {
                    throw new \InvalidArgumentException('arg1 must implement the StatementTargetInterface objectType not recognized:' . $value['objectType']);
                }
            }
            else {
                $value = new Activity($value);
            }
        }

        $this->target = $value;

        return $this;
    }

    /**
     * @return Activity|Agent|Group|StatementRef|SubStatement
     */
    public function getTarget() { return $this->target; }

    /**
     * @param Activity|Agent|Group|StatementRef|SubStatement|array $value
     * @return $this
     */
    public function setObject($value) { return $this->setTarget($value); }

    /**
     * @return Activity|Agent|Group|StatementRef|SubStatement
     */
    public function getObject() { return $this->getTarget(); }

    /**
     * @param Result|array $value
     * @return $this
     */
    public function setResult($value) {
        if (! $value instanceof Result && is_array($value)) {
            $value = new Result($value);
        }

        $this->result = $value;

        return $this;
    }

    /**
     * @return Result
     */
    public function getResult() { return $this->result; }

    /**
     * @param Context|array $value
     * @return $this
     */
    public function setContext($value) {
        if (! $value instanceof Context && is_array($value)) {
            $value = new Context($value);
        }

        $this->context = $value;

        return $this;
    }

    /**
     * @return Context
     */
    public function getContext() { return $this->context; }

    /**
     * @param \DateTime|string $value ISO 8601 timestamp
     * @throws \InvalidArgumentException if $value is not a string or DateTime object
     * @return $this
     */
    public function setTimestamp($value) {
        if (isset($value)) {
            if ($value instanceof \DateTime) {
                // Use format('c') instead of format(\DateTime::ISO8601) due to bug in format(\DateTime::ISO8601) that generates an invalid timestamp.
                $value = $value->format('c');
            }
            elseif (is_string($value)) {
                $value = $value;
            }
            else {
                throw new \InvalidArgumentException('type of arg1 must be string or DateTime');
            }
        }

        $this->timestamp = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimestamp() { return $this->timestamp; }
}
