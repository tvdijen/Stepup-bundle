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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @codeCoverageIgnore
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder;

        $rootNode = $treeBuilder->root('surfnet_bundle');
        $rootNode
            ->children()
                ->arrayNode('logging')
                    ->isRequired()
                    ->children()
                        ->scalarNode('application_name')
                            ->info('This is the application name that is included with each log message')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(function ($name) {
                                    return !is_string($name);
                                })
                                ->thenInvalid('surfnet_bundle.logging.application_name must be string')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('loa_definition')
                    ->children()
                        ->scalarNode('loa1')
                            ->example('https://gateway.tld/authentication/loa1')
                            ->isRequired()
                            ->validate()
                            ->ifTrue(function ($value) {
                                return !is_string($value);
                            })
                                ->thenInvalid('Loa definition for "loa1" must be a string')
                            ->end()
                        ->end()
                        ->scalarNode('loa2')
                            ->example('https://gateway.tld/authentication/loa2')
                            ->isRequired()
                            ->validate()
                            ->ifTrue(function ($value) {
                                return !is_string($value);
                            })
                                ->thenInvalid('Loa definition for "loa2" must be a string')
                            ->end()
                        ->end()
                        ->scalarNode('loa3')
                            ->example('https://gateway.tld/authentication/loa3')
                            ->isRequired()
                            ->validate()
                            ->ifTrue(function ($value) {
                                return !is_string($value);
                            })
                                ->thenInvalid('Loa definition for "loa3" must be a string')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        $this->createSmsConfiguration($rootNode);

        return $treeBuilder;
    }

    private function createSmsConfiguration(ArrayNodeDefinition $root)
    {
        $root
            ->children()
                ->arrayNode('sms')
                    ->info('SMS configuration')
                    ->isRequired()
                    ->children()
                        ->scalarNode('originator')
                            ->info('Originator (sender) for SMS messages')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(function ($value) {
                                    return (!is_string($value) || !preg_match('~^[a-z0-9]{1,11}$~i', $value));
                                })
                                ->thenInvalid(
                                    'Invalid SMS originator specified: "%s". Must be a string matching '
                                    . '"~^[a-z0-9]{1,11}$~i".'
                                )
                            ->end()
                        ->end()
                        ->integerNode('otp_expiry_interval')
                            ->info('After how many seconds an SMS challenge OTP expires')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(function ($value) {
                                    return $value <= 0;
                                })
                                ->thenInvalid(
                                    'Invalid SMS challenge OTP expiry, must be one or more seconds.'
                                )
                            ->end()
                        ->end()
                        ->integerNode('maximum_otp_requests')
                            ->info('How many challenges a user may request during a session')
                            ->isRequired()
                            ->validate()
                                ->ifTrue(function ($value) {
                                    return $value <= 0;
                                })
                                ->thenInvalid(
                                    'Maximum OTP requests has a minimum of 1'
                                )
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
