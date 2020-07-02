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

namespace Surfnet\StepupBundle\Tests\Request;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Surfnet\StepupBundle\Request\RequestId;

class RequestIdTest extends TestCase
{
    public function testItCanSetARequestId()
    {
        $generator = m::mock('Surfnet\StepupBundle\Request\RequestIdGenerator');

        $requestId = new RequestId($generator);
        $requestId->set('abcdef');
    }

    public function testItDoesNotAllowOverwritingTheRequestIdByDefault()
    {
        $this->setExpectedException('LogicException', 'not overwrite');

        $generator = m::mock('Surfnet\StepupBundle\Request\RequestIdGenerator');

        $requestId = new RequestId($generator);
        $requestId->set('abcdef');
        $requestId->set('abcdef');
    }

    public function testOverwritingTheRequestIdAfterSettingItIsAllowedIfForced()
    {
        $generator = m::mock('Surfnet\StepupBundle\Request\RequestIdGenerator');

        $requestId = new RequestId($generator);

        $requestId->set('12345');
        $requestId->set('abcde', true);

        $this->assertEquals('abcde', $requestId->get());
    }

    public function testOverwritingTheRequestIdAfterHavingItGeneratedIsAllowedIfForced()
    {
        $generator = m::mock('Surfnet\StepupBundle\Request\RequestIdGenerator')
            ->shouldReceive('generateRequestId')->once()->andReturn('12345')
            ->getMock();

        $requestId = new RequestId($generator);

        $requestId->get(); // trigger a generation
        $requestId->set('abcde', true);

        $this->assertEquals('abcde', $requestId->get());
    }

    public function testItGeneratesARequestIdIfItIsNotSet()
    {
        $generator = m::mock('Surfnet\StepupBundle\Request\RequestIdGenerator')
            ->shouldReceive('generateRequestId')->once()->andReturn('abcdef')
            ->getMock();

        $requestId = new RequestId($generator);

        $this->assertEquals('abcdef', $requestId->get());
    }
}
