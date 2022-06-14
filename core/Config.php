<?php

namespace Core;

class Config
{
    /**
     * @var array
     */
    private $settings =[];
    /**
     * @var self
     */
    private static $_instance;

    public function __construct($file)
    {
        $this->settings = require($file);
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function get($key)
    {
        if (!isset($this->settings[$key])) {
            return null;
        }

        return $this->settings[$key];
    }

    /**
     * @param string $file
     * @return self
     */
    public static function getInstance($file)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Config($file);
        }
        return self::$_instance;
    }
}