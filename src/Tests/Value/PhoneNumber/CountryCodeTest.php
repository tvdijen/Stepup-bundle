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

use stdClass;
use PHPUnit_Framework_TestCase as UnitTest;
use Surfnet\StepupBundle\Value\PhoneNumber\CountryCode;

class CountryCodeTest extends UnitTest
{
    /**
     * @test
     * @group value
     * @dataProvider invalidConstructorArgumentProvider
     *
     * @expectedException \Surfnet\StepupBundle\Exception\InvalidArgumentException
     *
     * @param mixed $argument
     */
    public function a_country_code_cannot_be_constructed_with_anything_but_a_string($argument)
    {
        new CountryCode($argument);
    }

    /**
     * @test
     * @group value
     *
     * @expectedException \Surfnet\StepupBundle\Value\Exception\UnknownCountryCodeException
     */
    public function a_country_code_cannot_be_created_with_a_non_existant_definition()
    {
        new CountryCode('this definition does not exist');
    }

    /**
     * @test
     * @group value
     */
    public function the_country_code_returns_the_definition_upon_request()
    {
        $definition = 'Turks and Caicos Islands (+1 649)';
        $countryCode = new CountryCode($definition);

        $this->assertEquals($definition, $countryCode->getCountryCodeDefinition());
    }

    /**
     * @test
     * @group value
     */
    public function the_country_code_returns_the_corresponding_unformatted_country_code_upon_request()
    {
        $definition = 'Solomon Islands (+677)';
        $code = 677;

        $countryCode = new CountryCode($definition);

        $this->assertEquals($code, $countryCode->getCountryCode());
    }

    /**
     * @test
     * @group value
     */
    public function country_codes_are_compared_by_definition()
    {
        $base = new CountryCode('Puerto Rico (+1 787)');
        $same = new CountryCode('Puerto Rico (+1 787)');
        $different = new CountryCode('Puerto Rico (+1 939)');

        $this->assertTrue($base->equals($same), 'Country codes with the same definition should be equal');
        $this->assertFalse($base->equals($different), 'Country codes with a different definition should not be equal');
    }

    /**
     * @test
     * @group value
     * @dataProvider toStringProvider
     *
     * @param string $definition
     * @param string $stringRepresentation
     */
    public function to_string_renders_a_correctly_formattted_string_representation($definition, $stringRepresentation)
    {
        $countryCode = new CountryCode($definition);
        $this->assertSame($stringRepresentation, $countryCode->__toString());
    }

    public function invalidConstructorArgumentProvider()
    {
        return [
            'int'           => [0],
            'float'         => [1.1],
            'boolean false' => [false],
            'boolean true'  => [true],
            'array'         => [[]],
            'object'        => [new stdClass()]
        ];
    }

    public function toStringProvider()
    {
        return [
            '4 digits'      => ['Puerto Rico (+1 787)', '+1 787'],
            '3 digits'      => ['Micronesia (+691)', '+691'],
            '2 digits'      => ['Netherlands (+31)', '+31'],
            '1 digit'       => ['Canada (+1)', '+1'],
            'Kazakhstan 76' => ['Kazakhstan (+76)', '+7 6'],
            'Kazakhstan 77' => ['Kazakhstan (+77)', '+7 7']
        ];
    }
}
