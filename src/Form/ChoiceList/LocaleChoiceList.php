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

namespace Surfnet\StepupBundle\Form\ChoiceList;

use Surfnet\StepupBundle\Exception\InvalidArgumentException;
use Symfony\Component\Form\Extension\Core\ChoiceList\LazyChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Intl;

final class LocaleChoiceList extends LazyChoiceList
{
    /**
     * @var string[]
     */
    private $locales;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @param string[] $locales
     * @param RequestStack $requestStack
     */
    public function __construct(array $locales, RequestStack $requestStack)
    {
        foreach ($locales as $index => $locale) {
            if (!is_string($locale)) {
                throw InvalidArgumentException::invalidType('string', sprintf('locales[%s]', $index), $locale);
            }
        }

        $this->locales = array_combine(
            $locales,
            array_map(
                function ($locale) {
                    return 'locale.' . $locale;
                },
                $locales
            )
        );

        $this->requestStack = $requestStack;
    }

    protected function loadChoiceList()
    {
        $currentLocale = $this->requestStack->getCurrentRequest()->getLocale();

        return new SimpleChoiceList($this->locales, [$currentLocale]);
    }
}
