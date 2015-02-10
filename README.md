# Step-up Bundle
[![Build Status](https://travis-ci.org/SURFnet/Stepup-bundle.svg)](https://travis-ci.org/SURFnet/Stepup-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SURFnet/Stepup-bundle/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/SURFnet/Stepup-bundle/?branch=develop) [![SensioLabs Insight](https://insight.sensiolabs.com/projects/5b8b8d8b-e917-4954-818b-782d9e181c75/mini.png)](https://insight.sensiolabs.com/projects/5b8b8d8b-e917-4954-818b-782d9e181c75)

A Symfony2 bundle that holds shared code and framework integration for all Step-up applications.

## Installation

 * Add the package to your Composer file
    ```sh
    composer require surfnet/stepup-bundle
    ```

 * Add the bundle to your kernel in `app/AppKernel.php`
    ```php
    public function registerBundles()
    {
        // ...
        $bundles[] = new Surfnet\StepupBundle\SurfnetStepupBundle;
    }
    ```

 * Copy and adjust the error templates to your application folder
    * `src/Resources/views/Exception/error.html.twig` → `app/Resources/SurfnetStepupBundle/views/Exception/error.html.twig`
    * `src/Resources/views/Exception/error404.html.twig` → `app/Resources/SurfnetStepupBundle/views/Exception/error404.html.twig`
