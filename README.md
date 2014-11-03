# Step-up Bundle
[![Build Status](https://travis-ci.org/SURFnet/Stepup-bundle.svg)](https://travis-ci.org/SURFnet/Stepup-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SURFnet/Stepup-bundle/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/SURFnet/Stepup-bundle/?branch=develop) [![SensioLabs Insight](https://insight.sensiolabs.com/projects/TODO/mini.png)](https://insight.sensiolabs.com/projects/TODO)

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
