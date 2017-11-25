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
 * A SubStatement is like a StatementRef in that it is included as part of a
 * containing Statement, but unlike a StatementRef, it does not represent an
 * event that has occurred.
 *
 * It can be used to describe, for example, a predication of a potential future
 * Statement or the behavior a teacher looked for when evaluating a student
 * (without representing the student actually doing that behavior).
 */
class SubStatement extends StatementBase implements StatementTargetInterface
{
    /** @inheritdoc */
    protected $objectType = 'SubStatement';

    /**
     * @inheritdoc
     */
    public function getObjectType() { return $this->objectType; }
}
