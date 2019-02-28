<?php

namespace TemplateEngineMustache;

use TemplateEngineFactory\TemplateEngineBase;

/**
 * Provides the Mustache template engine.
 */
class TemplateEngineMustache extends TemplateEngineBase
{
    const CACHE_DIR = 'TemplateEngineMustache_cache/';

    /**
     * @var \Mustache_Engine
     */
    protected $mustache;

    /**
     * {@inheritdoc}
     */
    public function render($template, $data = [])
    {
        $template = $this->normalizeTemplate($template);
        $data = $this->getData($data);

        return $this->getMustache()->render($template, $data);
    }

    /**
     * @throws \ProcessWire\WireException
     *
     * @return \Mustache_Engine
     */
    protected function getMustache()
    {
        if ($this->mustache === null) {
            return $this->buildMustache();
        }

        return $this->mustache;
    }

    /**
     * @throws \ProcessWire\WireException
     *
     * @return \Mustache_Engine
     */
    protected function buildMustache()
    {

        $config = array(
          'loader' => new \Mustache_Loader_FilesystemLoader($this->getTemplatesRootPath()),
          'cache' => $this->wire('config')->paths->assets . '/cache/' . self::CACHE_DIR
        );

        $partailsPath = $this->getTemplatesRootPath().'/partials';
        if(is_dir($partailsPath)) {
          $config['partials_loader'] = new \Mustache_Loader_FilesystemLoader($partailsPath);
        }

        $this->mustache = new \Mustache_Engine($config);

        $this->initMustache($this->mustache);

        return $this->mustache;
    }

    /**
     * Hookable method called after Mustache has been initialized.
     *
     * Use this method to customize the passed $mustache instance,
     * e.g. adding functions and filters.
     *
     * @param \Mustache_Engine $mustache
     */
    protected function ___initMustache(\Mustache_Engine $mustache)
    {
    }

    private function isDebug()
    {
        if ($this->moduleConfig['debug'] === 'config') {
            return $this->wire('config')->debug;
        }

        return (bool) $this->moduleConfig['debug'];
    }

    /**
     * @param object $data
     *
     * @throws \ProcessWire\WireException
     *
     * @return array
     */
    private function getData(array $data)
    {
      if (!$this->moduleConfig['api_vars_available']) {
          return $data;
      }
      foreach ($this->wire('all') as $name => $object) {
          $data[$name] = $object;
      }
      return $data;
    }

    /**
     * Normalize the given template by adding the template files suffix.
     *
     * @param string $template
     *
     * @return string
     */
    private function normalizeTemplate($template)
    {
        $suffix = $this->moduleConfig['template_files_suffix'];

        $normalizedTemplate = ltrim($template, DIRECTORY_SEPARATOR);

        if (!preg_match("/\.${suffix}$/", $template)) {
            return $normalizedTemplate . sprintf('.%s', $suffix);
        }

        return $normalizedTemplate;
    }

}
