# TemplateEngineMustache

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![ProcessWire 3](https://img.shields.io/badge/ProcessWire-3.x-orange.svg)](https://github.com/processwire/processwire)

A ProcessWire module adding Mustache to the [TemplateEngineFactory](https://github.com/wanze/TemplateEngineFactory).

## Requirements

* ProcessWire `3.0` or newer
* TemplateEngineFactory `2.0` or newer
* PHP `7.0` or newer
* Composer

> The `1.x` version of this module is available on the [1.x branch](https://github.com/blue-tomato/TemplateEngineMustache/tree/1.x).
Use this version if you still use _TemplateEngineFactory_ `1.x`.  

## Installation

Execute the following command in the root directory of your ProcessWire installation:

```
composer require blue-tomato/template-engine-mustache:^2.0
```

This will install the _TemplateEngineMustache_ and _TemplateEngineFactory_ modules in one step. Afterwards, don't forget
to enable Mustache as engine in the _TemplateEngineFactory_ module's configuration.

> ℹ️ This module includes test dependencies. If you are installing on production with `composer install`, make sure to
pass the `--no-dev` flag to omit autoloading any unnecessary test dependencies!.

## Configuration

The module offers the following configuration:

* **`Template files suffix`** The suffix of the Twig template files, defaults to `mustache`.
* **`Provide ProcessWire API variables in Mustache templates`** API variables (`$pages`, `$input`, `$config`...)
are accessible in Twig,
e.g. `{{ config }}` for the config API variable.
* **`Debug`** If enabled, Mustache outputs debug information.

## Extending Mustache

It is possible to extend Mustache after it has been initialized by the module. Hook the method `TemplateEngineMustache::initMustache`
to register custom functions, extensions, global variables, filters etc.

Here is an example how you can use the provided hook to attach a custom function.

```php

wire()->addHookAfter('TemplateEngineMustache::initMustache', function (HookEvent $event) {
    /** @var \Mustache_Engine $mustache */
    $mustache = $event->arguments('mustache');

    $mustache->setHelpers([
			'myHelperFunction' => function($text) {
				return trim($text);
			}
		]);
});

// ... and then use it anywhere in a Mustache template:

{{#myHelperFunction}} {{someVariable}} {{/myHelperFunction}}
```

> The above hook can be put in your `site/init.php` file. If you prefer to use modules, put it into the module's `init()`
method and make sure that the module is auto loaded.
