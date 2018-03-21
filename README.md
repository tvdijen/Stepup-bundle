# Step-up Bundle
[![Build Status](https://travis-ci.org/OpenConext/Stepup-bundle.svg)](https://travis-ci.org/OpenConext/Stepup-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/OpenConext/Stepup-bundle/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/OpenConext/Stepup-bundle/?branch=develop) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/5b8b8d8b-e917-4954-818b-782d9e181c75/mini.png)](https://insight.sensiolabs.com/projects/5b8b8d8b-e917-4954-818b-782d9e181c75)

A Symfony2 bundle that holds shared code and framework integration for all Step-up applications. See [Stepup-Deploy](https://github.com/OpenConext/Stepup-Deploy) for an overview of Stepup.

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

### Install resources

```twig
{% stylesheets filter='less'
'@SurfnetStepupBundle/Resources/public/less/stepup.less'
%}
<link href="{{ asset_url }}" type="text/css" rel="stylesheet" media="screen" />
{% endstylesheets %}
{% javascripts
'@SurfnetStepupBundle/Resources/public/js/stepup.js'
%}
<script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}
```

## Using the locale switcher

The locale switcher is a form that can be rendered with the help of a Twig function.

```twig
{% if app.user %}
    {% set locale_switcher = stepup_locale_switcher('handler_route', ['return-url' => app.request.uri]) %}
    {{ form_start(locale_switcher, { attr: { class: 'form-inline' }}) }}
    {{ form_widget(locale_switcher.locale) }}
    {{ form_widget(locale_switcher.switch) }}
    {{ form_end(locale_switcher) }}
{% endif %}
{% stylesheets filter='less'
'@SurfnetStepupBundle/Resources/public/less/style.less'
%}
<link href="{{ asset_url }}" type="text/css" rel="stylesheet" media="screen" />
{% endstylesheets %}
{% javascripts
'@SurfnetStepupBundle/Resources/public/js/index.js'
%}
<script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}
```

## Release strategy

### CHANGELOG
The changelog for the bundle is kept in the `./CHANGELOG` file. A history of the releases can be found in this file.
Previous RMT release notes are kept in this file for history purposes. Please use markdown to style the changelog.  

Please update the changelog with any notable changes that are introduced in an upcoming release. If you are not yet 
certain what the next release number will be, give the release title a generic value like `Upcoming release`. Make sure
before merging the changes to the release branch to update the release title in the changelog.

**Example CHANGELOG entry**
```
# 2.5.23
Brief explenation on the major changes of this release

## New features
 * Title of PR of the new feature #30
 * Support of POST binding for AuthnRequest #31
 
## Bugfixes
 * Title of PR of the bugfix #33

## Improvements
 * Title of PR of the improvement #29
 
```

When releasing a hotfix on a release branch, please update the changelog on the release branch and after releasing the
hotfix, also merge the hotfix to develop.