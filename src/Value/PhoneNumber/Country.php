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

namespace Surfnet\StepupBundle\Value\PhoneNumber;

final class Country
{
    /**
     * @var CountryCode
     */
    private $countryCode;

    /**
     * @var string
     */
    private $countryName;

    /**
     * @param CountryCode $countryCode
     * @param string $countryName
     */
    public function __construct(CountryCode $countryCode, $countryName)
    {
        if (!is_string($countryName)) {
            throw InvalidArgumentException::invalidType('string', 'countryName', $countryName);
        }

        $this->countryCode = $countryCode;
        $this->countryName = $countryName;
    }

    /**
     * @return CountryCode
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @param self $other
     * @return bool
     */
    public function equals(self $other)
    {
        return $this->countryName === $other->name && $this->countryCode->equals($other->countryCode);
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->countryName, $this->countryCode);
    }
}
