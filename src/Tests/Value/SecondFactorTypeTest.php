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

namespace Surfnet\StepupBundle\Tests\Value;

use PHPUnit_Framework_TestCase as TestCase;
use Surfnet\StepupBundle\Value\SecondFactorType;

final class SecondFactorTypeTest extends TestCase
{
    public function validTypes()
    {
        return [
            'sms' => ['sms'],
            'tiqr' => ['tiqr'],
            'yubikey' => ['yubikey'],
            'u2f' => ['u2f'],
            'biometric' => ['biometric'],
        ];
    }

    /**
     * @test
     * @group value
     * @dataProvider validTypes
     *
     * @param string $type
     */
    public function it_can_be_constructed($type)
    {
        new SecondFactorType($type);
    }

    /**
     * @test
     * @group value
     *
     * @expectedException \Surfnet\StepupBundle\Exception\InvalidArgumentException
     */
    public function it_doesnt_accept_integers()
    {
        new SecondFactorType(9);
    }

    /**
     * @test
     * @group value
     *
     * @expectedException \Surfnet\StepupBundle\Exception\DomainException
     */
    public function it_doesnt_accept_an_invalid_type()
    {
        new SecondFactorType('rosemary');
    }

    /**
     * @test
     */
    public function its_equality_is_determined_by_its_type()
    {
        $this->assertTrue((new SecondFactorType('sms'))->equals(new SecondFactorType('sms')));
    }

    /**
     * @test
     */
    public function its_type_can_be_verified()
    {
        $this->assertTrue((new SecondFactorType('sms'))->isSms());
        $this->assertTrue((new SecondFactorType('yubikey'))->isYubikey());
        $this->assertTrue((new SecondFactorType('tiqr'))->isTiqr());
        $this->assertTrue((new SecondFactorType('tiqr'))->isGssf());
        $this->assertTrue((new SecondFactorType('biometric'))->isBiometric());
        $this->assertTrue((new SecondFactorType('biometric'))->isGssf());
        $this->assertTrue((new SecondFactorType('u2f'))->isU2f());
    }

    /**
     * @test
     */
    public function they_can_be_compared()
    {
        $this->assertTrue(
            (new SecondFactorType('yubikey'))->hasEqualOrHigherLoaComparedTo(new SecondFactorType('sms')),
            'yubikey >= sms'
        );
        $this->assertTrue(
            (new SecondFactorType('sms'))->hasEqualOrHigherLoaComparedTo(new SecondFactorType('sms')),
            'sms >= sms'
        );
        $this->assertTrue(
            (new SecondFactorType('sms'))->hasEqualOrLowerLoaComparedTo(new SecondFactorType('yubikey')),
            'sms <= yubikey'
        );
        $this->assertTrue(
            (new SecondFactorType('sms'))->hasEqualOrLowerLoaComparedTo(new SecondFactorType('sms')),
            'sms <= sms'
        );
    }
}
