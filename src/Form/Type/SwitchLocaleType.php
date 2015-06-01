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

namespace Surfnet\StepupBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SwitchLocaleType extends AbstractType
{
    /**
     * @var \Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface
     */
    private $localeChoiceList;

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(ChoiceListInterface $localeChoiceList, UrlGeneratorInterface $urlGenerator)
    {
        $this->localeChoiceList = $localeChoiceList;
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction($this->urlGenerator->generate($options['route'], $options['route_parameters']));
        $builder->setMethod('POST');
        $builder->add('locale', 'choice', [
            'label' => /** @Ignore */ false,
            'required' => true,
            'widget_addon_prepend' => [
                'icon' => 'language'
            ],
            'choice_list' => $this->localeChoiceList,
        ]);
        $builder->add('switch', 'submit', [
            'label' => 'stepup_middleware_client.form.switch_locale.switch',
            'attr' => [ 'class' => 'btn btn-default' ],
        ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'route'            => null,
            'route_parameters' => [],
            'data_class'       => 'Surfnet\StepupBundle\Command\SwitchLocaleCommand',
        ]);

        $resolver->setRequired(['route']);

        $resolver->setAllowedTypes([
            'route'            => 'string',
            'route_parameters' => 'array',
        ]);
    }

    public function getName()
    {
        return 'stepup_switch_locale';
    }
}
