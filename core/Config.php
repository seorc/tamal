<?php

namespace tamal\core;

/* Copyright (C) 2012 Daniel Abraján
 *
 * This file is part of Tamal.
 *
 * Tamal is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * Tamal is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Tamal. If not, see <http://www.gnu.org/licenses/>.
 */

class NotFoundException extends \Exception {
}

/**
 * Configuration handling class.
 */
abstract class Config {
    public const EXPO_URL = 1;
    public const EXPO_DIR = 2;
    public const EXPO_VAL = 4;

    // Generic configuration values.
    public $debug = false;

    // Main class members.
    public $middleware = [];

    protected $descriptors = [];

    protected $_url = [];
    protected $_dir = [];
    protected $_val = [];

    public function __construct() {
        // The order is important here.
        $this->initRoutes();
        $this->initDBDescriptors();
        $this->initMiddleware();
    }

    final public function getDBDescriptor($name) {
        if (\array_key_exists($name, $this->descriptors)) {
            return $this->descriptors[$name];
        }
        throw new NotFoundException('The DBDescriptor was not found');
    }

    final public function exposeJson(
            $sections = self::EXPO_URL, $var_name = 'server') {
        $ex = [];
        if ($sections & self::EXPO_VAL) {
            $ex['val'] = $this->_val;
        }
        if ($sections & self::EXPO_DIR) {
            $ex['dir'] = $this->_dir;
        }
        if ($sections & self::EXPO_URL) {
            $ex['url'] = $this->_url;
        }

        return \json_encode($ex);
    }

    final public function url($name) {
        if (\array_key_exists($name, $this->_url)) {
            return $this->_url[$name];
        }
        // TODO Define Exception.
        throw new \Exception('Url not found');
    }

    final public function val($name) {
        if (\array_key_exists($name, $this->_val)) {
            return $this->_val[$name];
        }
        // TODO define Exception.
        throw new \Exception('Val not found');
    }

    final public function dir($name) {
        if (\array_key_exists($name, $this->_dir)) {
            return $this->_dir[$name];
        }
        //TODO Define Exception.
        throw new \Exception('Dir not found');
    }

    abstract protected function initRoutes();

    abstract protected function initMiddleware();

    abstract protected function initDBDescriptors();
}
