<?php

namespace Routeless\Core;

class Config
{
    public $path,
        $configuration = [];

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function get($name)
    {
        $path = explode('.', $name);
        $startPoint = array_shift($path);
        $config = $this->initConfig($startPoint);

        foreach ($path as $p) {
            if (!$config) break;
            $config = $config[$p] ?? null;
        }

        return $config;
    }

    protected function initConfig($startPoint)
    {
        if (!isset($this->configuration[$startPoint])) {
            $this->requireConfigFile($startPoint);
        }
        return $this->configuration[$startPoint];
    }

    protected function requireConfigFile($file)
    {
        $filePath = "{$this->path}/{$file}.php";
        $this->configuration[$file] = file_exists($filePath) ? require $filePath : null;
    }

}