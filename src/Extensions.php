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
 * Extensions are available as part of Activity Definitions, as part of a
 * Statement's "context" property, or as part of a Statement's "result" property.
 *
 * In each case, extensions are intended to provide a natural way to extend
 * those properties for some specialized use. The contents of these extensions
 * might be something valuable to just one application, or it might be a
 * convention used by an entire Community of Practice.
 *
 * Extensions are defined by a map and logically relate to the part of the
 * Statement where they are present. The values of an extension can be any
 * JSON value or data structure. Extensions in the "context" property provide
 * context to the core experience, while those in the "result" property provide
 * elements related to some outcome. Within Activities, extensions provide
 * additional information that helps define an Activity within some custom
 * application or Community of Practice. The meaning and structure of extension
 * values under an IRI key are defined by the person who controls the IRI.
 */
class Extensions extends Map implements ComparableInterface
{
    /**
     * Compares the instance with a provided instance for determining
     * whether an object received in a signature is a meaningful match.
     *
     * @param Extensions $fromSig
     * @return array ['success' => bool, 'reason' => string]
     */
    public function compareWithSignature($fromSig) {
        $sigMap = $fromSig->_map;

        $keys = array_unique(
            array_merge(
                isset($this->_map) ? array_keys($this->_map) : array(),
                isset($sigMap) ? array_keys($sigMap) : array()
            )
        );

        foreach ($keys as $key) {
            if (! isset($sigMap[$key])) {
                return array('success' => false, 'reason' => "$key not in signature");
            }
            if (! isset($this->_map[$key])) {
                return array('success' => false, 'reason' => "$key not in this");
            }
            if ($this->_map[$key] != $sigMap[$key]) {
                return array('success' => false, 'reason' => "$key does not match");
            }
        }

        return array('success' => true, 'reason' => null);
    }
}
