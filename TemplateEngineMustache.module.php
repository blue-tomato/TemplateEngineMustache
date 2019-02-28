<?php

namespace ProcessWire;

use TemplateEngineMustache\TemplateEngineMustache as MustacheEngine;

/**
 * Adds Mustache templates to the TemplateEngineFactory module.
 */
class TemplateEngineMustache extends WireData implements Module, ConfigurableModule
{
    /**
     * @var array
     */
    private static $defaultConfig = [
        'template_files_suffix' => 'mustache',
        'api_vars_available' => 1,
        'debug' => 'config',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->wire('classLoader')->addNamespace('TemplateEngineMustache', __DIR__ . '/src');
        $this->setDefaultConfig();
    }

    /**
     * @return array
     */
    public static function getModuleInfo()
    {
        return [
            'title' => 'Template Engine Mustache',
            'summary' => 'Mustache templates for the TemplateEngineFactory',
            'version' => 200,
            'author' => 'Blue Tomato',
            'href' => 'https://processwire.com/talk/topic/6834-module-Mustache-for-the-templateenginefactory/',
            'singular' => true,
            'autoload' => true,
            'requires' => [
                'TemplateEngineFactory>=2.0.0',
                'PHP>=7.0',
                'ProcessWire>=3.0',
            ],
        ];
    }

    public function init()
    {
        /** @var \ProcessWire\TemplateEngineFactory $factory */
        $factory = $this->wire('modules')->get('TemplateEngineFactory');

        $factory->registerEngine('Mustache', new MustacheEngine($factory->getArray(), $this->getArray()));
    }

    private function setDefaultConfig()
    {
        foreach (self::$defaultConfig as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param array $data
     *
     * @throws \ProcessWire\WireException
     * @throws \ProcessWire\WirePermissionException
     *
     * @return \ProcessWire\InputfieldWrapper
     */
    public static function getModuleConfigInputfields(array $data)
    {
        /** @var Modules $modules */
        $data = array_merge(self::$defaultConfig, $data);
        $wrapper = new InputfieldWrapper();
        $modules = wire('modules');

        /** @var \ProcessWire\InputfieldText $field */
        $field = $modules->get('InputfieldText');
        $field->label = __('Template files suffix');
        $field->name = 'template_files_suffix';
        $field->value = $data['template_files_suffix'];
        $field->required = 1;
        $wrapper->append($field);

        $field = $modules->get('InputfieldCheckbox');
        $field->label = __('Provide ProcessWire API variables in Mustache templates');
        $field->description = __('API variables (`$pages`, `$input`, `$config`...) are accessible in Mustache, e.g. `{{ config }}` for the config API variable.');
        $field->name = 'api_vars_available';
        $field->checked = (bool) $data['api_vars_available'];
        $wrapper->append($field);

        /** @var \ProcessWire\InputfieldSelect $field */
        $field = $modules->get('InputfieldSelect');
        $field->label = __('Debug');
        $field->name = 'debug';
        $field->addOptions([
            'config' => __('Inherit from ProcessWire'),
            0 => __('No'),
            1 => __('Yes'),
        ]);
        $field->value = $data['debug'];
        $wrapper->append($field);

        return $wrapper;
    }
}
