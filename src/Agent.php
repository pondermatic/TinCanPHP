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
 * An individual actor tracked using Statements performing an action within
 * an Activity. Is the "I" in "I did this".
 */
class Agent implements VersionableInterface, StatementTargetInterface, ComparableInterface
{
    use ArraySetterTrait, FromJSONTrait;

    /** @inheritdoc */
    protected $objectType = 'Agent';

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
     * Agent constructor.
     *
     * $arg elements:
     * * var AgentAccount|array $account
     * * var string $mbox mailto IRI
     * * var string $mbox_sha1sum
     * * var string $name
     * * var string $openid
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

        //
        // only one of these, note that if 'account' has been set
        // but is returned empty then no IFI will be included
        //
        if (isset($this->account)) {
            $versioned_acct = $this->account->asVersion($version);
            if (! empty($versioned_acct)) {
                $result['account'] = $versioned_acct;
            }
        }
        elseif (isset($this->mbox_sha1sum)) {
            $result['mbox_sha1sum'] = $this->mbox_sha1sum;
        }
        elseif (isset($this->mbox)) {
            $result['mbox'] = $this->mbox;
        }
        elseif (isset($this->openid)) {
            $result['openid'] = $this->openid;
        }

        return $result;
    }

    /**
     * Returns true if any of the inverse functional identifiers are set.
     *
     * @return bool
     */
    public function isIdentified() {
        return (isset($this->mbox) || isset($this->mbox_sha1sum) || isset($this->openid) || isset($this->account));
    }

    /**
     * Compares the instance with a provided instance for determining
     * whether an object received in a signature is a meaningful match.
     *
     * @param Agent $fromSig
     * @return array ['success' => bool, 'reason' => string]
     */
    public function compareWithSignature($fromSig) {
        //
        // having multiple IFIs set shouldn't cause a problem here
        // so long as all of their values match their counterparts
        //
        // we could allow for multiple IFIs in the `this` object and
        // ignore them missing in the signature but discussion ruled
        // against that need since the serialization via asVersion
        // shouldn't result in that ever for a valid statement
        //

        //
        // mbox and mbox_sha1sum are a special case where they can be
        // equal but have to be transformed for comparison, check them
        // first and short circuit
        //
        if (isset($this->mbox) && isset($fromSig->mbox_sha1sum)) {
            if ($this->getMbox_sha1sum() === $fromSig->mbox_sha1sum) {
                return array('success' => true, 'reason' => null);
            }

            return array('success' => false, 'reason' => 'Comparison of this.mbox to signature.mbox_sha1sum failed: no match');
        }
        elseif (isset($fromSig->mbox) && isset($this->mbox_sha1sum)) {
            if ($fromSig->getMbox_sha1sum() === $this->mbox_sha1sum) {
                return array('success' => true, 'reason' => null);
            }

            return array('success' => false, 'reason' => 'Comparison of this.mbox_sha1sum to signature.mbox failed: no match');
        }

        foreach (array('mbox', 'mbox_sha1sum', 'openid') as $property) {
            if (isset($this->$property) || isset($fromSig->$property)) {
                if ($this->$property !== $fromSig->$property) {
                    return array('success' => false, 'reason' => "Comparison of $property failed: value is not the same");
                }
            }
        }
        if (isset($this->account) || isset($fromSig->account)) {
            if (! isset($fromSig->account)) {
                return array('success' => false, 'reason' => "Comparison of account failed: value not in signature");
            }
            if (! isset($this->account)) {
                return array('success' => false, 'reason' => "Comparison of account failed: value not in this");
            }

            $acctResult = $this->account->compareWithSignature($fromSig->account);
            if (! $acctResult['success']) {
                return array('success' => false, 'reason' => "Comparison of account failed: " . $acctResult['reason']);
            }
        }

        return array('success' => true, 'reason' => null);
    }

    /**
     * @inheritdoc
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
    public function setMbox($value) {
        if (isset($value) && (! (stripos($value, 'mailto:') === 0))) {
            $value = 'mailto:' . $value;
        }
        $this->mbox = $value;
        return $this;
    }

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
    public function getMbox_sha1sum() {
        if (isset($this->mbox_sha1sum)) {
            return $this->mbox_sha1sum;
        }

        if (isset($this->mbox)) {
            return sha1($this->mbox);
        }
    }

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
     * @param AgentAccount|array $value
     * @return $this
     */
    public function setAccount($value) {
        if (! $value instanceof AgentAccount && is_array($value)) {
            $value = new AgentAccount($value);
        }

        $this->account = $value;

        return $this;
    }

    /**
     * @return AgentAccount
     */
    public function getAccount() { return $this->account; }
}
