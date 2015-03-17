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

namespace Surfnet\StepupBundle\Tests\Value\PhoneNumber;

use PHPUnit_Framework_TestCase as UnitTest;
use Surfnet\StepupBundle\Value\PhoneNumber\CountryCode;
use Surfnet\StepupBundle\Value\PhoneNumber\InternationalPhoneNumber;
use Surfnet\StepupBundle\Value\PhoneNumber\PhoneNumber;

class InternationalPhoneNumberTest extends UnitTest
{
    /**
     * @test
     * @group value
     */
    public function equality_is_based_on_country_code_and_phone_number_contents()
    {
        $base         = new InternationalPhoneNumber(new CountryCode('Netherlands (+31)'), new PhoneNumber('123'));

        $same         = new InternationalPhoneNumber(new CountryCode('Netherlands (+31)'), new PhoneNumber('123'));
        $otherCountry = new InternationalPhoneNumber(new CountryCode('Belgium (+32)'),     new PhoneNumber('123'));
        $otherNumber  = new InternationalPhoneNumber(new CountryCode('Netherlands (+31)'), new PhoneNumber('456'));
        $different    = new InternationalPhoneNumber(new CountryCode('Belgium (+32)'),     new PhoneNumber('456'));

        $this->assertTrue($base->equals($same));

        $this->assertFalse($base->equals($otherCountry), 'Not equal with different Country Code');
        $this->assertFalse($base->equals($otherNumber), 'Not equal with different Number');
        $this->assertFalse($base->equals($different), 'Not equal with different Country Code and different number');
    }

    /**
     * @test
     * @group value
     */
    public function msisdn_representation_is_formatted_correctly_as_a_continuous_string_of_digits()
    {
        $phoneNumber = new InternationalPhoneNumber(
            new CountryCode('Netherlands (+31)'),
            new PhoneNumber('0612345678')
        );

        $this->assertEquals('31612345678', $phoneNumber->toMSISDN());
    }
}
