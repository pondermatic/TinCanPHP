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
 * A group of actors tracked using Statements performing an action within
 * an Activity. Is the "I" in "I did this".
 */
class Group extends Agent
{
    /** @inheritdoc */
    protected $objectType = 'Group';

    /** @var Agent[] */
    protected $member;

    /**
     * Group constructor.
     *
     * $arg elements:
     * * var AgentAccount|array $account
     * * var string $mbox mailto IRI
     * * var string $mbox_sha1sum
     * * var Agent[]|array[] $member
     * * var string $name
     * * var string $openid
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        parent::__construct($arg);

        if (! isset($this->member)) {
            $this->setMember(array());
        }
    }

    /**
     * Collects defined object properties for a given version into an array.
     *
     * @param Version|string $version
     * @return array
     */
    public function asVersion($version) {
        $result = parent::asVersion($version);

        if (count($this->member) > 0) {
            $result['member'] = array();

            foreach ($this->member as $v) {
                array_push($result['member'], $v->asVersion($version));
            }
        }

        return $result;
    }

    /**
     * Compares the instance with a provided instance for determining
     * whether an object received in a signature is a meaningful match.
     *
     * @param Group $fromSig
     * @return array ['success' => bool, 'reason' => string]
     */
    public function compareWithSignature($fromSig) {
        //
        // if this group is identified then it is the comparison
        // of the identifier that matters
        //
        if ($this->isIdentified() || $fromSig->isIdentified()) {
            return parent::compareWithSignature($fromSig);
        }

        //
        // anonymous groups get their member list compared,
        // short circuit when they don't have the same length
        //
        if (count($this->member) !== count($fromSig->member)) {
            return array('success' => false, 'reason' => 'Comparison of member list failed: array lengths differ');
        }

        for ($i = 0; $i < count($this->member); $i++) {
            $comparison = $this->member[$i]->compareWithSignature($fromSig->member[$i]);
            if (! $comparison['success']) {
                return array('success' => false, 'reason' => "Comparison of member $i failed: " . $comparison['reason']);
            }
        }

        return array('success' => true, 'reason' => null);
    }

    /**
     * @param Agent[]|array[] $value
     * @return $this
     */
    public function setMember($value) {
        foreach ($value as $k => $v) {
            if (! $v instanceof Agent) {
                $value[$k] = new Agent($v);
            }
        }

        $this->member = $value;

        return $this;
    }

    /**
     * @return Agent[]
     */
    public function getMember() { return $this->member; }

    /**
     * @param Agent|array $value
     * @return $this
     */
    public function addMember($value) {
        if (! $value instanceof Agent) {
            $value = new Agent($value);
        }

        array_push($this->member, $value);

        return $this;
    }
}
