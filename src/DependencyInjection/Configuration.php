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

        $treeBuilder
            ->root('surfnet_bundle')
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

        return $treeBuilder;
    }
}
