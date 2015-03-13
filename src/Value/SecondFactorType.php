<?php

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupBundle\Value;

use Surfnet\StepupBundle\Exception\DomainException;
use Surfnet\StepupBundle\Exception\InvalidArgumentException;

final class SecondFactorType
{
    private static $loaLevelTypeMap = [
        'sms' => 2,
        'tiqr' => 2,
        'yubikey' => 3,
    ];

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        if (!is_string($type)) {
            throw InvalidArgumentException::invalidType('string', 'type', $type);
        }

        if (!isset(self::$loaLevelTypeMap[$type])) {
            throw new DomainException(
                sprintf(
                    "Invalid second factor type, got '%s', expected one of '%s'",
                    $type,
                    join(',', array_keys(self::$loaLevelTypeMap))
                )
            );
        }

        $this->type = $type;
    }

    /**
     * @param Loa $loa
     * @return bool
     */
    public function canSatisfy(Loa $loa)
    {
        $level = self::$loaLevelTypeMap[$this->type];

        return $loa->levelIsLowerOrEqualTo($level);
    }

    /**
     * @param Loa $loa
     * @return bool
     */
    public function isSatisfiedBy(Loa $loa)
    {
        $level = self::$loaLevelTypeMap[$this->type];

        return $loa->levelIsHigherOrEqualTo($level);
    }

    /**
     * @param SecondFactorType $other
     * @return bool
     */
    public function equals(SecondFactorType $other)
    {
        return $this->type === $other->type;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->type;
    }
}
