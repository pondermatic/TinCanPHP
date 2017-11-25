<?php
/*
    Copyright 2015 Rustici Software

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
 * The Person Object is very similar to an Agent Object, but instead of each
 * attribute having a single value, each attribute has an array value, and it
 * is legal to include multiple identifying properties.
 *
 * This is different from the FOAF
 * ([Friend Of A Friend](http://xmlns.com/foaf/spec/#term_Agent))
 * concept of person, person is being used here to indicate a person-centric
 * view of the LRS Agent data, but Agents just refer to one persona
 * (a person in one context).
 */
class Person implements VersionableInterface
{
    use ArraySetterTrait, FromJSONTrait;

    /** @var string */
    protected $objectType = 'Person';

    /** @var string */
    protected $name;

    /** @var string mailto IRI */
    protected $mbox;

    /** @var string */
    protected $mbox_sha1sum;

    /** @var string URI */
    protected $openid;

    /** @var AgentAccount */
    protected $account;

    /**
     * Person constructor.
     *
     * $arg elements:
     * * var AgentAccount $account
     * * var string $mbox mailto IRI
     * * var string $mbox_sha1sum
     * * var string $name
     * * var string $openid URI
     *
     * @param array $arg
     */
    public function __construct($arg = []) {
        if ($arg) {
            $this->_fromArray($arg);
        }
    }

    /**
     * Collects defined object properties for a given version into an array.
     *
     * @param Version|string $version
     * @return array
     */
    public function asVersion($version) {
        $result = array(
            'objectType' => $this->objectType
        );
        if (isset($this->name)) {
            $result['name'] = $this->name;
        }
        if (isset($this->account)) {
            $result['account'] = array();
            foreach ($this->account as $account) {
                if (! $account instanceof AgentAccount && is_array($account)) {
                    $account = new AgentAccount($account);
                }
                array_push($result['account'], $account->asVersion($version));
            }
        }
        if (isset($this->mbox_sha1sum)) {
            $result['mbox_sha1sum'] = $this->mbox_sha1sum;
        }
        if (isset($this->mbox)) {
            $result['mbox'] = $this->mbox;
        }
        if (isset($this->openid)) {
            $result['openid'] = $this->openid;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getObjectType() { return $this->objectType; }

    /**
     * @param string $value
     * @return $this
     */
    public function setName($value) { $this->name = $value; return $this; }

    /**
     * @return string
     */
    public function getName() { return $this->name; }

    /**
     * @param string $value mailto IRI
     * @return $this
     */
    public function setMbox($value) { $this->mbox = $value; return $this; }

    /**
     * @return string mailto IRI
     */
    public function getMbox() { return $this->mbox; }

    /**
     * @param string $value
     * @return $this
     */
    public function setMbox_sha1sum($value) { $this->mbox_sha1sum = $value; return $this; }

    /**
     * @return string
     */
    public function getMbox_sha1sum() {return $this->mbox_sha1sum;}

    /**
     * @param string $value URI
     * @return $this
     */
    public function setOpenid($value) { $this->openid = $value; return $this; }

    /**
     * @return string URI
     */
    public function getOpenid() { return $this->openid; }

    /**
     * @param AgentAccount $value
     * @return $this
     */
    public function setAccount($value) { $this->account = $value; return $this; }

    /**
     * @return AgentAccount
     */
    public function getAccount() { return $this->account; }
}
