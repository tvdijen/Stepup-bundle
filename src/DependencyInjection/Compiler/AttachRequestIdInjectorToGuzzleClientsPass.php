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

namespace Surfnet\StepupBundle\DependencyInjection\Compiler;

use GuzzleHttp\ClientInterface;
use Surfnet\StepupBundle\DependencyInjection\Configuration;
use Surfnet\StepupBundle\Guzzle\Subscriber\GuzzleRequestIdInjector;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class AttachRequestIdInjectorToGuzzleClientsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig('surfnet_stepup');
        $config = (new Processor())->processConfiguration(new Configuration(), $configs);

        // Attach Stepup request ID to outgoing requests of specific Guzzle clients
        if (!isset($config['attach_request_id_injector_to'])) {
            return;
        }

        foreach ($config['attach_request_id_injector_to'] as $guzzleServiceId) {
            $container->getDefinition($guzzleServiceId)->setConfigurator(
                function (ClientInterface $client) use ($container) {
                    /** @var GuzzleRequestIdInjector $requestIdInjector */
                    $requestIdInjector = $container->get('surfnet_stepup.guzzle.request_id_injector');
                    $client->getEmitter()->attach($requestIdInjector);
                }
            );
        }
    }
}
