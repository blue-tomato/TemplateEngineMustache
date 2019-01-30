TemplateEngineMustache
====================
ProcessWire module adding [Mustache](https://mustache.github.io/) templates to the TemplateEngineFactory.

## Installation
Install the module just like any other ProcessWire module. Check out the following guide: http://modules.processwire.com/install-uninstall/

This module requires TemplateEngineFactory: https://github.com/wanze/TemplateEngineFactory

After installing, don't forget to enable Mustache as engine in the TemplateEngineFactory module's settings.

## Configuration (over TemplateEngineProcesswire Module Configuration)
* **Path to templates** Path to folder where you want to store your Smarty template files.
* **Template files suffix** The suffix of the template files, default is *mustache*.

## Setting Helpers

```php
$view->setHelpers([
  'myHelperFunction' => function($text) {
    return trim($text);
  }
]);

```

## Examples


First expose data (in this case all story pages) to the mustache view being rendered next.
```php
// In file: /site/templates/stories.php

$stories = $pages->find('template=blogstory');
$view->set('stories', $stories);
```

Then use the passed in data (story pages) in your mustache template file.
```html
<!-- In file: /site/templates/views/stories.mustache -->

<h1>Stories</h1>
<ul>
{{#stories}}
  <li>
    <a href="{{url}}">
      {{title}}
    </a>
  </li>
{{/stories}}
</ul>
```