<?php

/**
 * Copyright 2016 SURFnet bv
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

namespace Surfnet\StepupBundle\Guzzle\Subscriber;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Message\Request;
use Mockery as m;
use PHPUnit_Framework_TestCase as UnitTest;
use Surfnet\StepupBundle\EventListener\RequestIdRequestResponseListener;
use Surfnet\StepupBundle\Request\RequestId;
use Surfnet\StepupBundle\Request\RequestIdGenerator;

class GuzzleRequestIdInjectorTest extends UnitTest
{
    public function testItSetsTheRequestIdAsHeader()
    {
        $requestId = new RequestId(m::mock(RequestIdGenerator::class));
        $requestId->set('abcde');

        $injector = new GuzzleRequestIdInjector($requestId);

        $request = m::mock(Request::class)
            ->shouldReceive('addHeader')
            ->once()
            ->withArgs([RequestIdRequestResponseListener::HEADER_NAME, 'abcde'])
            ->getMock();

        $event = m::mock(BeforeEvent::class)
            ->shouldReceive('getRequest')
            ->once()
            ->andReturn($request)
            ->getMock();

        $injector->addRequestIdHeader($event);
    }
}
