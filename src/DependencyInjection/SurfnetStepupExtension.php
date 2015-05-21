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

namespace Surfnet\StepupBundle\DependencyInjection;

use Surfnet\StepupBundle\Value\Loa;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SurfnetStepupExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), $config);

        $container->setParameter('logging.application_name', $config['logging']['application_name']);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');

        if (isset($config['loa_definition'])) {
            $this->defineLoas($config['loa_definition'], $container);
        } else {
            $container->removeDefinition('surfnet_stepup.service.loa_resolution');
        }

        $smsSecondFactorService = $container->getDefinition('surfnet_stepup.service.sms_second_factor');
        $smsSecondFactorService->replaceArgument(2, $config['sms']['originator']);

        $container
            ->getDefinition('surfnet_stepup.service.challenge_handler')
            ->replaceArgument(2, $config['sms']['otp_expiry_interval'])
            ->replaceArgument(3, $config['sms']['maximum_otp_requests']);

        $gatewayGuzzleOptions = [
            'base_url' => $config['gateway_api']['url'],
            'defaults' => [
                'auth' => [
                    $config['gateway_api']['credentials']['username'],
                    $config['gateway_api']['credentials']['password'],
                    'basic'
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        ];

        $gatewayGuzzle = $container->getDefinition('surfnet_stepup.guzzle.gateway_api');
        $gatewayGuzzle->replaceArgument(0, $gatewayGuzzleOptions);
    }

    private function defineLoas(array $loaDefinitions, ContainerBuilder $container)
    {
        $loaService = $container->getDefinition('surfnet_stepup.service.loa_resolution');

        $loa1 = new Definition('Surfnet\StepupBundle\Value\Loa', [Loa::LOA_1, $loaDefinitions['loa1']]);
        $loa2 = new Definition('Surfnet\StepupBundle\Value\Loa', [Loa::LOA_2, $loaDefinitions['loa2']]);
        $loa3 = new Definition('Surfnet\StepupBundle\Value\Loa', [Loa::LOA_3, $loaDefinitions['loa3']]);

        $loaService->addArgument([$loa1, $loa2, $loa3]);
    }
}
